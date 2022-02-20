<?php
#    casserole.php - Egg SGML 
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

function casserole_dish($env,$extension,$altextension,$extoptional,$doc,$self_href) {
	$c = $m = null; $b = 0;$k = null; $u = null;
	$d = null;

	$env->self_href = $self_href;
	$u = $env->to_file_number_one( $self_href, '' );
	if( $u[0] != 200 ) {
		http_response_code(404); }
	if( ! isset($u[1]) ) return;
	$env->templatefile = $u[1];

	$d = load_eggsgml_file_env( $env, $env->templatefile );
	if( ! $d ) return;

		$m = eggsgml_descendent( $d, 'cache_control' );
		if( $m ) if( attribute_exists($m,'static') ) {
	m:		$c = apache_request_headers();
			if( array_key_exists('If-Modified-Since',$c) ) {
				$b = strtotime($c['If-Modified-Since']);
				if( filemtime($env->templatefile) <= $b ) {
					http_response_code(304);
					return; }
			}
			header('Last-Modified: ' . date('r',filemtime($env->templatefile)));
			header('Cache-Control: max-age=0');
		} else if( attribute_exists($m,'dynamic') ) {
	n:		header('Last-Modified: ' . date('r',$env->scriptnow));
			header('Cache-Control: max-age=0');
		} else if( attribute_exists($m,'querystring') ) {
			if( array_key_exists( 'REDIRECT_QUERY_STRING', $_SERVER ) ) {
				goto n; }
			goto m;
		}
		$k = new pasta( $d, $doc );
	//domegg;
	//$k->tgcnode = $eggenv->stack;
	//$k->writernode = ( $eggenv->stack->q ? $eggenv->stack : $eggenv->stack->writernode );
	//$k->dn = $this->d;
//		$eggenv->enqueue( $k, [ ] );

	//$k = newframe(new tgc_generic(dirname($doc,1),$env), new echo_out, $d);
	//if( ! $env->nodoctype ) {
	//	echo ("<!doctype html>"); }
		return $k;
}

class casserole {
	public $ts;
	public $idents = [ 'red:main', 'wall' ], $perfectbliss = 1;
	public $plate;
	function write($a) { }
	function substitute( $libconfig, $dn, $dnpath ) {
//.			$path = $_SERVER['DOCUMENT_ROOT'];
		if( $dn->nodeName == 'main' ) {
//.			$env = $this->lc;
//.			$libconfig->nodoctype = true;
			$libconfig->interimjsupgrade = true;
			$libconfig->secrets_path = apply_relative_path( $dnpath, $dn->getAttribute('secrets-path') );
			$libconfig->urlpath = $_GET['t'];
			$libconfig->api = basename(dirname($_SERVER['PHP_SELF']));
			if( file_exists( apply_relative_path( $_SERVER['DOCUMENT_ROOT'] , 'shipyard.txt' ) ) ) {
				if( attribute_exists( $dn, 'shipyard-auth' ) ) {
					$libconfig->shipyard_auth = $dn->getAttribute('shipyard-auth');
				} else {
					$libconfig->shipyard_auth = file_get_contents( apply_relative_path( $_SERVER['DOCUMENT_ROOT'], 'shipyard.txt' ) );
				}
				$libconfig->shipyard = true; }
			$libconfig->fallback = $dn->getAttribute('fallback');
			$libconfig->rootdoc = $dn->getAttribute('rootdoc');
			$libconfig->file_ext = $dn->getAttribute('extension');
			$libconfig->altextension = $dn->getAttribute('alt-extension');
			$libconfig->extoptional = attribute_exists($dn,'extension-optional');
				//if( ! $this->NF ) return 1;

			while( attribute_exists( $dn, "redirect-to-ssl" ) ) {
				if( array_key_exists('HTTPS',$_SERVER) )
					if( $_SERVER['HTTPS'] == 'on' ) break;
				header('Location:https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				return; 
			}
			if( ! check_shipyard_auth($libconfig,$_SERVER['DOCUMENT_ROOT']) ) {
				$this->plate = casserole_dish( $libconfig, $dn->getAttribute('extension'), $dn->getAttribute('alt-extension'), attribute_exists($dn,'extension-optional'), $dnpath . attribute_with_inival( $dn, 'shipyard-doc', '/shipyard'), '/shipyard');
				//if( ! $this->NF ) return 1;
				return; }
			if( $_GET['t'] == '/' ) {
				$this->plate = casserole_dish( $libconfig, $dn->getAttribute('extension'), $dn->getAttribute('alt-extension'), attribute_exists($dn,'extension-optional'), $dnpath . '/' . $dn->getAttribute('rootdoc'), $_GET['t'] );
				//if( ! $this->NF ) return 1;
				return;
			}
			if( $_GET['t'] == '/' . $dn->getAttribute('rootdoc') ) {
				header('Location:' . $this->self_protocol($dn) . '://' . $_SERVER['HTTP_HOST'] . '/' );
				return;
			}
			$this->plate = casserole_dish( $libconfig, $dn->getAttribute('extension'), $dn->getAttribute('alt-extension'), attribute_exists($dn,'extension-optional'), $dnpath . strtolower(str_replace('.','',$_GET['t'])), strtolower($_GET['t']) );
			//if( ! $this->NF ) return 1;
			return;
		}
	}
	function tap( $chicken, $str ) {
		$m = $d = null;
		if( $str ) {
//			enqueue_modules( $eggenv, $doc, $env );
			$chicken->enqueue( $this->plate );
			return; }
		$m = $_SERVER['DOCUMENT_ROOT'];
		$d = load_eggsgml_file( apply_relative_path( $_SERVER['DOCUMENT_ROOT'], 'templates_config.xml' ) );
		$chicken->enqueue( $this );
		pasta_enqueue( $chicken, $d, $_SERVER['DOCUMENT_ROOT'] );
	}
};

?>
