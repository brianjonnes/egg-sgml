<?php

class tgc_server_variables__item {
	public $w, $k;
	function open() {
		$this->k = array_keys($_SERVER);
		return count($this->k) > 0;
	}
	function start( $q ) {
		$this->w = 0;
		return 0; }
	function repeat( $q ) {
		$this->w += 1;
		if( $this->w < count($this->k) ) return 1;
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'var_name' ) {
			if( $end) return 0;
			$q->write( sr_amp_lt( $this->k[$this->w] ) );
			return 1; }
		if( $w->nodeName == 'var_value' ) {
			if( $end ) return 0;
			$q->write( sr_amp_lt( $_SERVER[$this->k[$this->w]] ) );
			return 2;
		}
		return 0;
	}
};

class tgc_server_variables {
	public $m;
	function open() {
		$this->m = new tgc_server_variables__item;
		return $this->m->open();
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'item' ) {
			if( $end ) return 1;
			$this->NF = newframe( $this->m, $q, $w );
			return 3;
		}
		return 0;
	}
};

class mtgc_server_variables {
	public $NF;
	function initialize( $path, $env ) {
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$a = 00;
		if( $w->nodeName == 'server_variable_list' ) {
			if( $end ) return 1;
			$a = new tgc_server_variables;
			if( $a->open() ) {
				$this->NF = newframe( $a, $q, $w );
				return 3; }
			return 1;
		}
		return 0;
	}
};

return new mtgc_server_variables;