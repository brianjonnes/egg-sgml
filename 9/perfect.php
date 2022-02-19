<?php
#    perfect.php - Egg SGML 
#    Copyright 2020, 2021, 2022 Brian Jonnes

#    Egg-SGML is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, version 3 of the License.

#    Egg-SGML is distributed
#    WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with Egg-SGML.  If not, see <https://www.gnu.org/licenses/>.

function enqueue_modules( $eggenv, $doc, $env ) {
}

//NOT in 70331
//FOUND in 70333
if(PHP_VERSION_ID < 70333 ) {
  function mb_chr($j) { /* Documentation indicates this should be in 7.2 */
	if($j==39) return '\'';
	return html_entity_decode('&#'.$j.';'); }
}

function load_eggsgml_file( $doc ) {
	$k = $n = $u = $m = 00;
	
	$m = new W3CDOM_tagreceiver();
	$u = new eggsgml_parser($m);
	load_eggsgml_file_2( $m, $u, $doc );
	return $m->w;
}

function load_eggsgml_file_env( $env, $doc ) {
	$u = null;

	$m = new W3CDOM_tagreceiver();
	$u = new eggsgml_parser($m);
	$u->interimjsupgrade = true;
	load_eggsgml_file_2( $m, $u, $doc );
	return $m->w;
}

function sr_amp_lt( $x ) {
	return str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) );
}

function sr_amp_quot( $x ) {
	return str_replace('"','&quot;', str_replace( '&', '&amp;', $x ) );
}

function sr_25( $x, $k ) {
	$d = 00; $n = '';
	for( $d = 0; $d < strlen($x); $d += 1 ) {
		if( $x[$d] == '%' || strpos($k,$x[$d]) !== false ) {
			$n .= '%' . str_pad( dechex(ord($x[$d])), 2, '0', STR_PAD_LEFT );
		} else $n .= $x[$d];
	}
	return $n;
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

function perfect_script($w1) {
	return $w1->nodeType == 3;
}

function check_shipyard_auth($env,$u) {
	if( ! $env->shipyard ) return true;
	$m = $env->shipyard_auth;
	if( $m === false ) return true;
	if( array_key_exists( 'shipyard', $_COOKIE ) ) {
		if( $_COOKIE['shipyard'] === $m ) {
			goto K; } }
	if( array_key_exists( 'shipyard', $_GET ) ) {
		if( $_GET['shipyard'] === $m ) {
	K:		setcookie('shipyard',$m,time()+864000);
			return true; } }
}

function apply_relative_path_gnu( $abs, $rel ) {
	$f = 00; $t = 00; $g = 00; $s = strlen($abs)-1;

	while(1) {
		$t = strpos( $rel, '/', $f );
		if( $t === false ) {
			switch( substr( $rel, $f ) ) {
			case '..':
				$g = strrpos( $abs, '/', $s - strlen($abs) );
				if( $g === false ) {
					break; }
				$s = $g - 1;
			case '.':
				$g = strrpos( $abs, '/', $s - strlen($abs) );
				if( $g === false ) {
					break; }
				$s = $g - 1;
			default:
				if( $abs[$s] == '/' ) {
					$abs = substr( $abs, 0, $s + 1 ) . substr( $rel, $f );
				} else {
					$abs = substr( $abs, 0, $s + 1 ) . '/' . substr( $rel, $f );
				}
				break;	
			}
			break;
		}
		switch( substr( $rel, $f, $t - $f ) ) {
		case '':
			break;
		case '.':
		k:	$g = strrpos( $abs, '/', $s - strlen($abs) );
			if( $g === false ) {
				break; }
			$s = $g - 1;
			break;
		case '..':
			$g = strrpos( $abs, '/', $s - strlen($abs) );
			if( $g === false ) {
				break; }
			$s = $g - 1;
			goto k;
		default:
			if( $abs[$s] == '/' ) {
				$abs = substr( $abs, 0, $s + 1 ) . substr( $rel, $f, $t - $f );
			} else {
				$abs = substr( $abs, 0, $s + 1 ) . '/' . substr( $rel, $f, $t - $f );
			}
			$s = strlen($abs)-1;
		}
		$f = $t + 1;
	}
	return $abs;
}

function apply_relative_path_swin( $abs, $rel ) {
	$f = 00; $t = 00; $g = 00; // $s

	while(1) {
		$t = strpos( $rel, '\\', $f );
		if( $t === false ) {
			if( $abs[strlen($abs)-1] == '\\' ) {
				$abs .= substr( $rel, $f );
			} else {
				$abs .= '\\' . substr( $rel, $f );
			}
			break;
		}

		switch( substr( $rel, $f, $t - $f ) ) {
		case '.':
		k:	$g = strrpos( $abs, '\\' );
			if( $g === false )
				break;
			$abs = substr( $abs, 0, $g );
			break;
		case '..':
			$g = strrpos( $abs, '\\' );
			if( $g === false )
				break;
			$abs = substr( $abs, 0, $g );
			goto k;
		case '':
			break;
		default:
			if( $abs[strlen($abs)-1] == '\\' ) {
				$abs .= substr( $rel, $f, $t - $f );
			} else {
				$abs .= '\\' . substr( $rel, $f, $t - $f );
			}
			break;
		}
		$f = $t + 1;
	}
	return $abs;
}

function apply_relative_path( $abs, $rel ) {
	if( $abs[0] == '/' ) {
		return apply_relative_path_gnu( $abs, $rel );
	}
	return apply_relative_path_swin( $abs, $rel );
}

function attribute_exists( $w, $n ) {
	$m = 00;
	if( $w->attributes ) for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
		if( $w->attributes->item($m)->name == $n ) return true; }
	return false;
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
