<?php
#    eggsgml.php - Egg SGML nuts & bolts
#    Copyright 2020, 2021 Brian Jonnes

#    Egg-SGML is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, version 3 of the License.

#    Egg-SGML is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with Egg-SGML.  If not, see <https://www.gnu.org/licenses/>.

class frame {
	public $T;
	public $c;
	public $q;
	public $V;
}

class dev_null {
	function write($m) {
	}
}

function newframe($c,$q,$T) {
	$NF = new frame();
	$NF->T = $T;
	$NF->c = $c;
	$NF->q = $q;
	$NF->V = array();
	return $NF;
}

class tgc {
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		return 0; }
}

class echo_out {
	function write( $d ) {
		echo $d;
	}
}

function sr_amp_lt( $x ) {
	return str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) );
}

function sr_amp_quot( $x ) {
	return str_replace('"','&quot;', str_replace( '&', '&amp;', $x ) );
}

function write_attributes( $q, $w, $f ) {
	$m = 00;
	for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
		if( array_search( $w->attributes->item($m)->name, $f ) === false ) {
			$q->write(' ' . $w->attributes->item($m)->name);
			if( $w->attributes->item($m)->value != null ) {
				$q->write('="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
		}
	}
}
// We discover, by accident as it were, that Brian's way of getting the job done is best.
// If one knows how to get it done, it does not do to refer to the documentation to see if there
// is a smpler way of doing it.
// We end up drawing circles on the walls, looking into mirrors, reading dusty old books, and 
function attribute_with_inival( $w, $N, $initial ) {
// the subroutine remains in the above state until we finally have to revert the changes.
		if( attribute_exists( $w, $N ) ) {
//		if( attribute_exists( $w, $N ) {
//	We include the mistake that has to get corrected all too often - not being careful enough with brackets.
			return $w->getAttribute($N); }
// At this point, the coder thinks to themselves, is this the best way to proceed?
		return $initial;
// It'll do for now, I suppose.
}

function eggsgml($F) {
	$x = $F->T->firstChild;
	if ( $x == null ) 
		return;
	while (1) {
		$h = 1;
		while ($h) {
//++
			if( $x->nodeType == 8 ) {
				break; }
//+++
			if ( $x->nodeType == 3 ) {
				$F->c->consume_text( $F->q, $x->data );
				break; }
//			if x.nodeType == 7:
//				F_ = F
//				while 1:
//					if x.target in F_.V:
//						F.q.write( F_.V[x.target].replace('&','&amp;').replace('<','&lt;') )
//						break
//					F_ = F_.P
//					if F_ == None:
//						F.q.write( '&' + x.target + ';')
//						break
//				break
			$_F = $F;
			while (1) {
				$a = $_F->c->consume( $F->q, 0, $x );
				if( $a == 1 ) {
					$h = 0;
					break; }
				if( $a == 2 ) {
					$b = $x->firstChild;
					if( $b != null ) {
						$x = $b;
					} else {
						$h = 0; }
					break; }
				if( $a == 3 ) {
					$NF = $_F->c->NF;
					$_F->c->NF = null;
					$b = $NF->T->firstChild;
					if( $b == null ) {
						$h = 0;
						break; }
					$F->x = $x;
					$NF->P = $F;
					$F = $NF;
					$x = $b;
					$F->c->start( $F->q );
					break; }
				$_F = $_F->P;
//++
				if( $_F == null ) {
					$h = 0;
					break; }
//+++
			}
		}
		while (1) {
			if( $x->nodeType == 1 ) {
				$_F = $F;
				while (1) {
					if( $_F->c->consume( $F->q, 1, $x ) ) {
						break; }
					$_F = $_F->P; }
			}
			$b = $x->nextSibling;
			if( $b != null ) {
				$x = $b;
				break; }
			$b = $x->parentNode;
			if( $b === $F->T ) {
				if( $F->c->repeat($F->q) ) {
					$x = $F->T->firstChild;
					break; }
				$F = $F->P;
				if( $F == null ) {
					return; }
				$x = $F->x;
			} else {
				$x = $b;
			}
		}
	}
}

function eggsgml_descendent($T,$t) {
	$x = $T->firstChild;
	if ( $x == null ) 
		return;
	while (1) {
		while (1) {
			if( $x->nodeType == 8 ) {
				break; }
			if ( $x->nodeType == 3 ) {
				break; }
			if( $x->nodeName == $t ) {
				return $x; }
			$b = $x->firstChild;
			if( $b != null ) {
				$x = $b;
			} else {
				break; }
		}
		while (1) {
			$b = $x->nextSibling;
			if( $b != null ) {
				$x = $b;
				break; }
			$b = $x->parentNode;
			if( $b === $T ) {
				return null;
			} else {
				$x = $b;
			}
		}
	}
}
?>