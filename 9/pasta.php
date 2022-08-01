<?php
# Copyright: GPL; Copyleft: Technews Publishing, South Africa.

class sane_enqueue_result {
	public $egg;
};

function pasta_enqueue($env,$T,$path) {
	$b = null; $h = null; $r = new sane_enqueue_result;
	$c = $T->lastChild;
	while ($c) {
		do {
			if( $c->nodeType == 1 ) {
				if( array_key_exists( 'red:' . $c->nodeName, $env->diplomats[0] ) ) {
					$h = $env->diplomats[0]['red:' . $c->nodeName];
				} else if( array_key_exists( 'red', $env->diplomats[0] ) ) {
					$h = $env->diplomats[0]['red'];
				} else goto b;
		d:		$r = $h[count($h)-1]->substitute( $env->libconfig, $c, $path );
				if( $r ) {
					if( array_key_exists(1, $r) )
						$env->enqueue( $r[1] );
					switch( $r[0] ) {
					case 0: break;
					case 1: goto b; 
					case 2:
						$h = new antipasta($c,$path);
						goto c; }
				}
				break;
			} else if( $c->nodeType == 3 ) {
				if( array_key_exists( 'yellow', $env->diplomats[0] ) ) {
					$h = $env->diplomats[0]['yellow'];
				} else goto b;
				goto d;
			}
		b:	$h = new pasta($c,$path);
		c:	$env->enqueue( $h );
		} while(0);
		$c = $c->previousSibling;
	}
}

class pasta {
	public $ts;
	public $idents = [ ], $perfectbliss = 0;
	public $dn, $path;
	function __construct ($dn, $path) {
		$this->dn = $dn;
		$this->path = $path;
	}
	function tap( $env, $str ) {
		$w1 = null;
		switch( $this->dn->nodeType ) {
		case 3:
			$env->write( sr_amp_lt( $this->dn->data ) );
			break;
		case 9: 
			if( $str ) {
				return; }
	_2b:		pasta_enqueue( $env, $this->dn, $this->path );
			break;
		case 8:
			break;
		default:
			if( $str ) {
				$env->libconfig->write_close_tag($env,$this->dn->nodeName);
				return; }
			$env->enqueue( $this );
			pasta_enqueue( $env, $this->dn, $this->path );

			$env->write('<' . $this->dn->nodeName);
			write_attributes( $env, $this->dn, [ ] );
			$env->libconfig->write_end_of_tag($env,$this->dn->nodeName);
			break;
		}
	}
};

class antipasta {
	public $ts;
	public $idents = [ ], $perfectbliss = 0;
	public $dn, $path;
	function __construct ($dn, $path) {
		$this->dn = $dn;
		$this->path = $path;
	}
	function tap( $env, $str ) {
		pasta_enqueue( $env, $this->dn, $this->path );
	}
};

