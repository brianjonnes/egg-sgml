<?php
#    
#    Copyright (C) 2020 Brian Jonnes

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

class null_buffer2 {
	function write($m) { }
};

class mtgc_module_test__modules {
	public $a;
	function __construct() {
		$this->a = array();
	}
};

class mtgc_module_test__urls {
	public $a;
	function __construct() {
		$this->a = array();
	}
};

class tgc_module_test__empty {
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
		if( $this->inner && $w == $this->w ) {
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

class tgc_module_test__a {
	public $NF;
	public $path, $env;
	public $testpath;
	public $modules, $urls;
	function __construct($path,$env,$testpath,$modules,$urls) {
		$this->path = $path;
		$this->env = $env;
		$this->testpath = $testpath;
		$this->modules = $modules;
		$this->urls = $urls;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'module' ) {
			if( $end ) return 1;
			if( ! array_key_exists( $w->getAttribute('path'), $this->modules->a ) ) {
				$this->modules->a[$w->getAttribute('path')] = 1;
				$a = include $this->testpath . '/' . $w->getAttribute('path');
			}
			return 2; }
		if( $w->nodeName == 'a' ) {
			if( $end ) return 1;
			if( ! array_key_exists( 'a ' . $w->getAttribute('href'), $this->urls->a ) ) {
				$this->urls->a['a ' . $w->getAttribute('href')] = 1; }
			return 2; }
		if( $w->nodeName == 'link' ) {
			if( $end ) return 1;
			if( ! array_key_exists( 'link ' . $w->getAttribute('href'), $this->urls->a ) ) {
				$this->urls->a['link ' . $w->getAttribute('href')] = 1; }
			return 2; }
		if( $w->nodeName == 'img' ) {
			if( $end ) return 1;
			if( ! array_key_exists( 'img ' . $w->getAttribute('src'), $this->urls->a ) ) {
				$this->urls->a['img ' . $w->getAttribute('src')] = 1; }
			return 2; }
		if( $w->nodeName == 'include' ) {
			if ($end) return 1;
			$a = $this->testpath . "/" . $w->getAttribute('path');
			$m = load_eggsgml_file( $a );
			if( dirname($a,1) != $this->testpath ) {
				$this->NF = newframe( new tgc_module_test__a($this->path,$this->env,dirname($a,1),$this->modules), $q, $m );
			} else {
				$this->NF = newframe(new tgc,$q,$m);
			}
			return 3; }
		return 2;
	}
};

class tgc_module_test__modules_item {
	public $NF;
	public $path, $env;
	public $modules;
	function __construct($path,$env,$modules) {
		$this->path = $path;
		$this->env = $env;
		$this->modules = array_keys($modules->a);
	}
	function start( $q ) {
		$this->m = 0;
		return 0; }
	function repeat( $q ) {
		$this->m += 1;
		return $this->m < count($this->modules); }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'module_name' ) {
			if( $end ) return 1;
			$q->write( sr_amp_lt( $this->modules[$this->m] ) );
			return 1; }
		return 0;
	}
};

class tgc_module_test__modules {
	public $NF;
	public $path, $env;
	public $modules;
	function __construct($path,$env,$modules) {
		$this->path = $path;
		$this->env = $env;
		$this->modules = $modules;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$a = 00; $d = $k = $n = null;
		if( $w->nodeName == 'item' ) {
			$this->NF = newframe(new tgc_module_test__modules_item($this->path,$this->env,$this->modules),$q,$w);
			return 3;
		}
		return 0;
	}
};

class tgc_module_test__url_item {
	public $NF;
	public $path, $env;
	public $urls;
	function __construct($path,$env,$urls) {
		$this->path = $path;
		$this->env = $env;
		$this->urls = array_keys($urls->a);
	}
	function start( $q ) {
		$this->m = 0;
		return 0; }
	function repeat( $q ) {
		$this->m += 1;
		return $this->m < count($this->urls); }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'url_value' ) {
			if( $end ) return 1;
			$q->write( sr_amp_lt( $this->urls[$this->m] ) );
			return 1; }
		return 0;
	}
};

class tgc_module_test__urls {
	public $NF;
	public $path, $env;
	public $urls;
	function __construct($path,$env,$urls) {
		$this->path = $path;
		$this->env = $env;
		$this->urls = $urls;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$a = 00; $d = $k = $n = null;
		if( $w->nodeName == 'item' ) {
			$this->NF = newframe(new tgc_module_test__url_item($this->path,$this->env,$this->urls),$q,$w);
			return 3;
		}
		return 0;
	}
};

class tgc_module_test__files_item {
	public $NF;
	public $path, $env;
	function __construct($path,$env) {
		$this->path = $path;
		$this->env = $env;
		$this->n = opendir($_SERVER['DOCUMENT_ROOT']);
		$this->a = "";
	}
	function read() {
		while( ( $this->a = readdir($this->n) ) !== false )
				if( strtoupper( substr($this->a,strlen($this->a)-4) ) == strtoupper( $this->env->file_ext ) ) {
					return true; }
		return false;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return $this->read(); }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'file_name' ) {
			if( $end ) return 1;
			$q->write( sr_amp_lt( $this->a ) );
			return 1; }
		return 0;
	}
};

class tgc_module_test__files {
	public $NF;
	public $path, $env;
	public $no_files;
	function __construct($path,$env) {
		$this->path = $path;
		$this->env = $env;
		$this->y = new tgc_module_test__files_item($this->path,$this->env);
		$this->no_files = ! $this->y->read();
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$a = 00; $d = $k = $n = null;
		if( $w->nodeName == 'empty' ) {
			return 1; }
		if( $w->nodeName == 'item' ) {
			if( $end ) return 1;
			if( $this->no_files ) {
				return 1; }
			$this->NF = newframe($this->y,$q,$w);
			return 3;
		}
		return 0;
	}
};

class mtgc_module_test {
	public $NF;
	public $path, $env;
	public $c, $modules;
	function initialize($path,$env) {
		$this->path = $path;
		$this->env = $env;
		$this->modules = new mtgc_module_test__modules;
		$this->urls = new mtgc_module_test__urls;
		$this->c = new tgc_module_test__a($path,$env,$_SERVER['DOCUMENT_ROOT'],$this->modules,$this->urls);
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		$a = 00; $d = $k = $n = null;
		if( $w->nodeName == 'module_list' ) {
			if( $end ) return 1;
			$n = opendir( $_SERVER['DOCUMENT_ROOT'] );
			while( ( $a = readdir($n) ) !== false ) {
				if( strtoupper( substr($a,strlen($a)-strlen($this->env->file_ext)) ) == strtoupper( $this->env->file_ext ) ) {
					$d = load_eggsgml_file( $_SERVER['DOCUMENT_ROOT'] . '/' . $a );
					$k = newframe($this->c, new null_buffer2, $d);
					$k->P = null;
					eggsgml($k);
				}
			}
			$this->NF = newframe(new tgc_module_test__modules($this->path,$this->env,$this->modules),$q,$w);
			return 3; }
		if( $w->nodeName == 'url_list' ) {
			if( $end ) return 1;
			$this->NF = newframe( new tgc_module_test__urls($this->path,$this->env,$this->urls),$q,$w);
			return 3; }
		if( $w->nodeName == 'file_list' ) {
			if( $end ) return 1;
			$k = new tgc_module_test__files($this->path,$this->env);
			if( ! $k->no_files ) {
				$this->NF = newframe($k,$q,$w);
			} else {
				$this->NF = newframe(new tgc_module_test__empty,$q,$w);
			}
			return 3; }
		return 0;
	}
}

return new mtgc_module_test;

?>