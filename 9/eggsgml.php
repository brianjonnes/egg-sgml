<?php
# Copyright: GPL; Copyleft: Technews Publishing, South Africa.

class eggsgml_sauce_pasta {
	public $ts;
	public $idents = [ ], $perfectbliss = 0;
	public $dn, $path, $hlight;
	public $q;
	function __construct ($hlight2,$q, $dn, $path) {
		$this->q = $q;
		$this->dn = $dn;
		$this->path = $path;
		$this->hlight = $hlight2;
	}
	function tap( $env, $str ) {
		$w1 = null; $a = 0; $m = 0;
		switch( $this->dn->nodeType ) {
		case 3:
			$env->write( sr_amp_lt( sr_amp_lt( $this->dn->data ) ) );
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
				if( $this->dn->firstChild != null ) {
					$a = 0;
					if( ! ( array_search($this->dn->nodeName,$this->hlight) === false ) )  {
						$env->write('<b>'); $a = true; }
					$env->write('&lt;/<u>' . $this->dn->nodeName . '</u>>');
					if( $a ) $env->write('</b>'); 
				}
				return;
			}
			$env->enqueue( $this );
			pasta_enqueue( $env, $this->dn, $this->path );

			$a = 0;
			if( ! ( array_search($this->dn->nodeName,$this->hlight) === false ) )  {
				$env->write('<b>'); $a = true; }
			$env->write('&lt;<u>' . $this->dn->nodeName . '</u>');
			for( $m = 0; $m < $this->dn->attributes->length; $m += 1 ) {
				$env->write(' ' . $this->dn->attributes->item($m)->name);
				if( $this->dn->attributes->item($m)->value != null ) {
					$env->write('="' . str_replace('"',"&amp;quot;", str_replace( "&", "&amp;amp;", $this->dn->attributes->item($m)->value ) ) . '"' ); }
			}
			if( $this->dn->firstChild == null ) {
				$env->write('/'); }
			$env->write('>');
			if( $a ) $env->write('</b>'); 
			break;
		}
	}
};
class eggsgml_sauce {
	public $ts;
	public $idents = [ 'red', 'yellow', 'wall' ], $perfectbliss = 1;
	public $dn, $path, $hlight;
	public $q;
	function __construct ($q,$highlight, $dn, $path) {
		$this->q = $q;
		$this->dn = $dn;
		$this->path = $path;
		$this->hlight = explode(' ', $highlight);
	}
	function write($m) {
		$this->q->write($m);
	}
	function substitute( $libconfig, $dn, $dnpath ) {
		return [ 0, new eggsgml_sauce_pasta($this->hlight,$this,$dn,$dnpath) ];
	}
	function tap( $env, $str ) {
		if( $str ) {
			return; }
		$env->enqueue( $this );
		pasta_enqueue( $env, $this->dn, $this->path );
	}
};

function eggsgml_module( $path, $env, $w ) {
	$a = include $_SERVER['DOCUMENT_ROOT'] . '/' . $env->api . '/' . $w->getAttribute('path');
	$a->initialize( $path, $w );
	return $a;
}

class eggsgml_pasta {
	public $ts; public $idents = [ ], $perfectbliss = 0;

	public $dn, $dnpath;
	function __construct( $dn, $dnpath ) {
		$this->dn = $dn; $this->dnpath = $dnpath; }

	function tap( $chicken, $str ) {
		$c = ''; $m = null; $f = 0; $a = '';
		switch($this->dn->nodeName) {
		case 'play':
			if( array_key_exists( $this->dn->getAttribute('id'), $chicken->libconfig->clips ) ) {
				pasta_enqueue( $chicken, $chicken->libconfig->clips[$this->dn->getAttribute('id')], $chicken->libconfig->clip_path[$this->dn->getAttribute('id')] ); }
			break;
		case 'record':
			$chicken->libconfig->clips[$this->dn->getAttribute('id')] = $this->dn;
			$chicken->libconfig->clip_path[$this->dn->getAttribute('id')] = $this->dnpath;
			break;
		case 'form.cache_dynamic':
			if( $str ) {
				$chicken->write('</form>'); return; }
			$chicken->enqueue( $this );
			pasta_enqueue( $chicken, $this->dn, $this->dnpath );
			$chicken->write('<form');
			write_attributes( $chicken, $this->dn, [ 'action' ] );
			if( $this->dn->getAttribute('method') == 'post' ) {
				$chicken->write(' action="' . sr_amp_quot( $_SERVER['REDIRECT_URL'] ) . '?' . rand(0,100) . '"' ); }
			$chicken->write('>');
			break;
		case 'shipyard-log':
			$chicken->write( sr_amp_lt( $chicken->libconfig->shipyard_log ) );
			break;
		case 'doctype':
			$chicken->write_doctype( $this->dn->getAttribute('raw') );
			break;
		case 'eggsgml_version':
			$c = eggsgml_version();
			if( attribute_exists( $this->dn, 'decimal' ) )
				$c = str_replace( '.', $this->dn->getAttribute('decimal'), $c );
			$chicken->write( sr_amp_lt( $c ) );
			break;
		case 'eggsgml_api_version':
			$chicken->write( sr_amp_lt( $chicken->libconfig->api ) );
			break;
		case 'a.site':
			if( $str ) {
				$chicken->write('</a>');
				return; }
			$chicken->enqueue( $this );
			pasta_enqueue( $chicken, $this->dn, $this->dnpath );
			$chicken->write('<a');
			$c = $this->dn->getAttribute('href');
			$f = strpos( $c, '?' );
			if( $f ) $c = substr( $c, 0, $f );
			$c = $chicken->libconfig->to_file_number_one( $c, '' );
			if( $c == $chicken->libconfig->templatefile ) {
				$f = strmerge( $this->dn->getAttribute('activeclass'), $this->dn->getAttribute('class'), ' ' );
			} else {
				$f = $this->dn->getAttribute('class');
			}
			if( $f != '' ) {
				$chicken->write(' class="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $f ) ) . '"' );
			}
			write_attributes( $chicken, $this->dn, [ 'class', 'activeclass' ] );
			$chicken->write('>');
			break;
		case 'module':
			$chicken->enqueue( eggsgml_module($this->dnpath,$chicken->libconfig,$this->dn) );
			break;
		case 'showsource':
			$a = apply_relative_path( $this->dnpath, '.' );
			$a = apply_relative_path( $a, $this->dn->getAttribute('path') );
			$m = load_eggsgml_file_env( $chicken->libconfig, $a );
			$chicken->enqueue( new eggsgml_sauce( $chicken->get_writer(), $this->dn->getAttribute('highlight'), $m, $a ) );
			break;
		case 'showphp':
			$a = apply_relative_path( $this->dnpath, '.' );
			$a = apply_relative_path( $a, $this->dn->getAttribute('path') );
			$m = file_get_contents( $a );
			$env->write(str_replace("\n", "<br/>", str_replace("\t", "&nbsp; &nbsp; &nbsp; ", str_replace("<","&lt;", str_replace( "&", "&amp;", $m ) ) ) ) );
			break;
		case 'servervariable':
			if( array_key_exists($this->dn->getAttribute('name'),$_SERVER) ) {
				if( attribute_exists( $this->dn, 'exists-yn' ) ) {
					$env->write('yes');
				} else {
					$env->write(sr_amp_lt($_SERVER[$this->dn->getAttribute('name')]));
				}
			} else {
				if( attribute_exists( $this->dn, 'exists-yn' ) ) {
					$env->write('no'); }
			}
			break;
		}
	}
};

class eggsgml {
	public $ts, $perfectbliss = 0;
	public $idents = [ 'wall', 'red:script', 'red:record', 'red:play', 'red:include', 'red:cache_control', 'red:form.cache_dynamic', 'red:shipyard-log', 'red:doctype', 'red:sailing', 'red:shipyard', 'red:eggsgml_version', 'red:eggsgml_api_version', 'red:a.site', 'red:module', 'red:showsource', 'red:showphp', 'red:redirect', 'red:servervariable' ];
	function substitute( $libconfig, $dn, $dnpath ) {
		$a = '';
		switch( $dn->nodeName ){ 
		case 'redirect':
			$a = $w->getAttribute('location');
			if( attribute_exists( $dn, 'withquery' ) && array_key_exists('REDIRECT_QUERY_STRING',$_SERVER) ) {
				$a .= '?' . str_replace('#','%2B',$_SERVER['REDIRECT_QUERY_STRING']); }
			header('Location:' . $a);
			return [ 0 ];
		case 'sailing':
			if( $libconfig->shipyard ) {
				return [ 0 ]; }
			return [ 2 ];
		case 'shipyard':
			if( $libconfig->shipyard ) {
				return [ 2 ]; }
			return [ 0 ];
		case 'cache_control':
			return [ 2 ];
		case 'script':
			return [ 0, new script($dn,$dnpath) ];
		case 'record':
		case 'module':
		case 'form.cache_dynamic':
		case 'a.site':
			return [ 0, new eggsgml_pasta($dn,$dnpath) ];
		case 'play':
		case 'shipyard-log':
		case 'doctype':
		case 'eggsgml_version':
		case 'eggsgml_api_version':
		case 'showsource':
		case 'showphp':
		case 'servervariable':
			return [ 2, new eggsgml_pasta($dn,$dnpath) ];
		case 'include':
			$a = apply_relative_path( $dnpath, '.' );
			$a = apply_relative_path( $a, $dn->getAttribute('path') );
			$m = load_eggsgml_file_env( $libconfig, $a );
			return [ 2, new pasta( $m, $a ) ];
		}
		return [ 1 ];
	}
	function write($h) {
		echo $h;
	}
	function tap( $chicken, $str ) {
	}
};

?>
