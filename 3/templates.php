<?php
#    templates.php - Egg SGML
#    Copyright (C) 2021 Brian Jonnes

#    This library is free software; you can redistribute it and/or
#    modify it under the terms of the GNU Lesser General Public
#    License as published by the Free Software Foundation; either
#    version 2.1 of the License, or (at your option) any later version.

#    This library is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
#    Lesser General Public License for more details.

#    You should have received a copy of the GNU Lesser General Public
#    License along with this library; if not, write to the Free Software
#    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

include 'eggsgml.php';
include 'eggsgml-loader.php';
include 'origin.php';
include 'tgc_generic.php';

function main_f($extension,$extoptional,$doc,$self_href) {
	do {
		if( $extension != '' ) {
			if( file_exists( $doc . $extension ) ) {
				$d = load_eggsgml_file( $doc . $extension );
				break; }
			if( ! $extoptional ) {
				http_response_code(404);
				return; }
		}
		if( ! file_exists( $doc ) ) {
			http_response_code(404);
			return; }			
		$d = load_eggsgml_file( $doc  );
	} while(0);
	$k = newframe(new tgc_generic(dirname($doc,1),$self_href), new echo_out, $d);
	echo ("<!doctype html>");
	return $k;
}

function attribute_exists( $w, $n ) {
	$m = 00;
	if( $w->attributes ) for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
		if( $w->attributes->item($m)->name == $n ) return true; }
	return false;
}

class tgc_templates {
	public $NF;
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$u = 00;
		if( $w->nodeName == 'main' ) {
			if( $end ) return 1;
			if( array_key_exists( 'c', $_GET ) ) {
				$u = '..'; } else { $u = '../..'; }
			if( ! attribute_exists( $w, "redirect-to-ssl" ) )
				if( ! $_SERVER['HTTPS'] ) {
					header('Location:https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
					return 1; }
			if( ! check_shipyard_auth($u) ) {
				$this->NF = main_f( $w->getAttribute('extension'), attribute_exists($w,'extension-optional'), $u .'/shipyard', '/shipyard');
				return 3; }
			if( $_GET['t'] == '/' ) {
				$this->NF = main_f( $w->getAttribute('extension'), attribute_exists($w,'extension-optional'), $u .'/' . $w->getAttribute('rootdoc'), $_GET['t'] );
				if( ! $this->NF ) return 0;
				return 3;
			}
			if( $_GET['t'] == '/' . $w->getAttribute('rootdoc') ) {
				header('Location:https://' . $_SERVER['HTTP_HOST'] . '/' );
				return 1;
			}
			$this->NF = main_f( $w->getAttribute('extension'), attribute_exists($w,'extension-optional'), $u . $_GET['t'], $_GET['t'] );
			if( ! $this->NF ) return 0;
			return 3;
		}
		return 0;
	}
};

function check_shipyard_auth($u) {
	if( ! file_exists($u . "/shipyard.txt") ) return true;
	$m = file_get_contents($u . '/shipyard.txt');
	if( $m === false ) return true;
	if( array_key_exists( 'shipyard', $_COOKIE ) ) {
		if( $_COOKIE['shipyard'] === $m ) {
			return true; } }
	if( array_key_exists( 'shipyard', $_GET ) ) {
		if( $_GET['shipyard'] === $m ) {
			setcookie('shipyard',$m);
			return true; } }
}

function main() {
	$u = 00;
	if( array_key_exists( 'c', $_GET ) ) {
		$u = '..'; } else { $u = '../..'; }
	if( file_exists( $u . '/templates_config.xml' ) ) {
		$d = load_eggsgml_file( $u . '/templates_config.xml' );
	} else {
		$d = load_eggsgml_file( 'templates_config.xml' );
	}
	$k = newframe(new tgc_templates, new echo_out, $d);
	$k->P = null;
	test($k);
}

main();

?>