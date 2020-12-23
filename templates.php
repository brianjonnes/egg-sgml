<?php

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

//function load_file($m) {
//	$d = new DomDocument();
//	$d->load("asdf.html");
//	return $d;
//}

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
	function consume( $q, $end, $w ) {
		return 0; }
}

class echo_out {
	function write( $d ) {
		echo $d;
	}
}

function test($F) {
	$F->q->write("<!doctype html>");
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
				$F->q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x->data ) ) );
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

class tgc_sgml_source {
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume( $q, $end, $w ) {
		if( $end ) {
			if( $w->firstChild != null ) {
				$q->write('<b>&lt;/<u>' . $w->tagName . '</u>></b>'); }
			return 1; }
		$q->write('<b>&lt;<u>' . $w->tagName . '</u>');
		for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
			$q->write(' ' . $w->attributes->item($m)->name);
			if( $w->attributes->item($m)->value != null ) {
				$q->write('="' . str_replace('"',"&amp;quot;", str_replace( "&", "&amp;amp;", $w->attributes->item($m)->value ) ) . '"' ); }
		}
		if( $w->firstChild == null ) {
			$q->write('/'); }
		$q->write('></b>');
		return 2; }
}

class tgc_test {
	public $path, $self_href;
	function __construct($path,$self_href) {
		$this->path = $path;
		$this->self_href = $self_href;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume( $q, $end, $w ) {
		if( $w->tagName == 'tag' ) {
			return 2; }
		if( $w->tagName == 'a.site' ) {
			if( $end ) {
				if( $w->firstChild != null ) {
					$q->write('</a>'); }
				return 1; }
			$q->write('<a');
			for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
				if( $w->attributes->item($m)->name == 'activeclass' ) {
					if( $w->getAttribute('href') == $this->self_href ) {
						$q->write(' class="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
				} else {
					$q->write(' ' . $w->attributes->item($m)->name);
					if( $w->attributes->item($m)->value != null ) {
						$q->write('="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
				}
			}
			if( $w->firstChild == null ) {
				$q->write('/'); }
			$q->write('>');
			return 2; }
		if( $w->tagName == 'include' ) {
			if ($end) return 1;
			$m = new DomDocument;
			$m->load( $this->path . "/" . $w->getAttribute('path') );
			$this->NF = newframe(new tgc,$q,$m);
			return 3; }
		if( $w->tagName == 'showsource' ) {
			if ($end) return 1;
			$m = new DomDocument;
			$m->load( $this->path . "/" . $w->getAttribute('path') );
			$this->NF = newframe(new tgc_sgml_source,$q,$m);
			return 3; }
		if( $w->tagName == 'showphp' ) {
			if ($end) return 1;
			$m = file_get_contents($this->path . "/" . $w->getAttribute('path'));
			$q->write(str_replace("\n", "<br/>", str_replace("\t", "&nbsp; &nbsp; &nbsp; ", str_replace("<","&lt;", str_replace( "&", "&amp;", $m ) ) ) ) );
			return 2; }
		if( $w->tagName == 'redirect' ) {
			if ($end) return 1;
			header('Location:' . $w->getAttribute('location'));
			return 1; }
		if( $w->tagName == 'record' ) {
			if ($end) return 1;
			$this->clips[$w->getAttribute('id')] = $w;
			return 1; }
		if( $w->tagName == 'play' ) {
			if ($end) return 1;
			if( array_key_exists( $w->getAttribute('id'), $this->clips ) ) {
				$this->NF = newframe(new tgc,$q,$this->clips[$w->getAttribute('id')]);
				return 3; }
			return 1; }
		if( $w->tagName == 'servervariable' ) {
			if ($end) return 1;
			$q->write(str_replace('<','&lt;',str_replace('&','&amp;',$_SERVER[$w->getAttribute('name')])));
			return 1; }
		if( $end ) {
			if( $w->firstChild != null ) {
				$q->write('</' . $w->tagName . '>'); }
			return 1; }
		$q->write('<' . $w->tagName);
		for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
			$q->write(' ' . $w->attributes->item($m)->name);
			if( $w->attributes->item($m)->value != null ) {
				$q->write('="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
		}
		if( $w->firstChild == null ) {
			$q->write('/'); }
		$q->write('>');
		return 2; }
}

function main($doc,$self_href) {
	$d = new DomDocument();
	$d->load($doc);
	$k = newframe(new tgc_test(dirname(__FILE__,2),$self_href), new echo_out, $d);
	$k->P = null;
	test($k);
}

function check_shipyard_auth() {
	if( ! file_exists("../shipyard.txt") ) return true;
	$m = file_get_contents('../shipyard.txt');
	if( $m === false ) return true;
	if( array_key_exists( 'shipyard', $_COOKIE ) ) {
		if( $_COOKIE['shipyard'] === $m ) {
			return true; } }
	if( array_key_exists( 'shipyard', $_GET ) ) {
		if( $_GET['shipyard'] === $m ) {
			setcookie('shipyard',$m);
			return true; } }
}

function metamain() {
	if( $_SERVER['HTTPS'] ) {
		header( 'Strict-Transport-Security: max-age=333300; includeSubDomains; preload' );
		if( ! check_shipyard_auth() ) {
			main('../shipyard', '/shipyard');
			return; }
		if( $_GET['t'] == '/' ) {
			main('../index', $_GET['t']);
		} else if( $_GET['t'] == '/index' ) {
			header('Location:https://' . $_SERVER['HTTP_HOST'] . '/' );
		} else {
			main('..' . $_GET['t'], $_GET['t'] );
		}
	} else {
		header('Location:https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
}

metamain();

?>