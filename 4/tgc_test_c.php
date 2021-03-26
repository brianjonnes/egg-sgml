<?php

class tgc_test_util__empty {
	public $NF;
	public $inner, $w;
	function start( $q ) {
		$this->inner = false;
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		if( $this->inner ) {
			$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	}
	function consume( $q, $end, $w ) {
		if( $this->inner && $w === $this->w ) {
			// $end 
			$this->inner = false;
			return 1; }
		if( $w->nodeName == 'empty' ) {
			if( $this->inner )
				return 0;
			if( $end ) {
				// ! $w == $this->w
				return 1; }
			$this->inner = true;
			$this->w = $w;
			return 2;
		}
		if( $this->inner ) 
			return 0;
		return 2;
	}
};

class tgc_test_c__a {
	public $NF, $path, $env;
	public $clipname;
	function __construct($path,$env,$clipname) {
		$this->path = $path;
		$this->env = $env;
		$this->clipname = $clipname;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) { }
	function consume( $q, $end, $w ) {
		switch( $w->nodeName ) {
		case 'record':
			if( $end ) return 1;
			if( $w->getAttribute('id') == $this->clipname ) {
				$this->NF = newframe( new tgc_sgml_source(''), $q, $w );
				return 3; }
			return 1;
		}
		if( $end ) return 1;
		return 2;
	}
};

class tgc_test_c__page {
	public $NF, $path, $env;
	public $n, $a;
	function __construct($path,$env,$n,$a,$clipname) {
		$this->path = $path;
		$this->env = $env;
		$this->n = $n;
		$this->a = $a;
		$this->c = new tgc_test_c__a($path,$env,$clipname);
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		while(1) {
			$this->a = readdir($this->n);
			if( $this->a === false ) {
				return 0; }
			if( strtoupper( substr($this->a,strlen($this->a)-strlen($this->env->file_ext)) ) == strtoupper( $this->env->file_ext ) ) {
				return 1; }
		}
	}
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$d = null;
		switch($w->nodeName) {
		case 'clip_value':
			if( $end ) return 1;
			$d = load_eggsgml_file( $_SERVER['DOCUMENT_ROOT'] . '/' . $this->a );
			$this->NF = newframe($this->c, $q, $d);
			return 3;
		case 'page_name':
			if( $end ) return 1;
			$q->write( sr_amp_lt( $this->a ) );
			return 2;
		}
		return 0;
	}
};

class tgc_test_c__pages {
	public $NF, $path, $env;
	public $n, $a;
	function __construct($path,$env,$clipname) {
		$this->path = $path;
		$this->env = $env;
		$this->clipname = $clipname;
	}
	function open() {
		$this->n = opendir( $_SERVER['DOCUMENT_ROOT'] );
		while(1) {
			$this->a = readdir($this->n);
			if( $this->a === false ) {
				return false; }
			if( strtoupper( substr($this->a,strlen($this->a)-strlen($this->env->file_ext)) ) == strtoupper( $this->env->file_ext ) ) {
				return true; }
		}
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		switch( $w->nodeName ) {
		case 'item':
			if( $end ) return 1;
			$this->NF = newframe( new tgc_test_c__page($this->path,$this->env,$this->n,$this->a,$this->clipname), $q, $w );
			return 3;
		}
		return 0;
	}
};

class mtgc_test_c {
	public $NF;
	public $path, $env;
	public $clipname;
	function initialize($path,$env) {
		$this->path = $path;
		$this->env = $env;
		if( array_key_exists('clip',$_GET) ) {
			$this->clipname = $_GET['clip']; }
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$c = null;
		switch($w->nodeName) {
		case 'pages':
			if( $end ) return 1;
			$c = new tgc_test_c__pages($this->path,$this->env,$this->clipname);
			if( $c->open() ) {
				$this->NF = newframe( $c, $q, $w );
			} else {
				$this->NF = newframe( new tgc_test_util__empty, $q, $w );
			}
			return 3;
		}
		return 0;
	}
}

return new mtgc_test_c;
?>