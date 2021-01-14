<?php

class tgc_ignore {
	public $NF;
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) { }
	function consume( $q, $end, $w ) {
			if( $end ) return 1;
			return 2;
	}
};

class tgc_html_body {
	public $NF;
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'body' ) {
			if( $end ) return 1;
			return 2;
		}
		if( $w->nodeName == 'h1' ) {
			$this->NF = newframe( new tgc_ignore(), $q, $w );
			return 3;
		}
		if( $end ) {
			if( $w->firstChild != null ) {
				$q->write('</' . $w->nodeName . '>'); }
			return 1; }
		$q->write('<' . $w->nodeName);
		if( $w->attributes ) for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
			$q->write(' ' . $w->attributes->item($m)->name);
			if( $w->attributes->item($m)->value != null ) {
				$q->write('="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
		}
		if( $w->firstChild == null ) {
			if( $w->nodeName == 'div' || $w->nodeName == 'i' ) {
				$q->write('></' . $w->nodeName);
			} else { $q->write('/'); }
		}
		$q->write('>');
		return 2; }
};

class tgc_html_doc {
	public $NF;
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
	//	$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) );
	}
	function consume( $q, $end, $w ) {
		if( $end ) return 1;
		if( $w->nodeName == 'body' ) {
			$this->NF = newframe( new tgc_html_body(), $q, $w );
			return 3;
		}
		return 2;
	}
};

class tgc_module_root {
	public $NF;
	public $path, $self_href;
	function __construct($path,$self_href) {
		$this->path = $path;
		$this->self_href = $self_href;
	}
	function start( $q ) {
		return 0; }
	function repeat( $q ) {
		return 0; }
	function consume_text( $q, $x ) {
		$q->write(str_replace("<","&lt;", str_replace( "&", "&amp;", $x ) ) ); }
	function consume( $q, $end, $w ) {
		if( $w->nodeName == 'include_html' ) {
			if( $end) return 1;
			$m = load_eggsgml_file( $this->path . '/' . $w->getAttribute('path') );
			$this->NF = newframe(new tgc_html_doc,$q,$m);
			return 3; }
		if( $w->nodeName == 'include_html_md' ) {
			if ($end) return 1;
			$m = load_eggsgml_file( $this->path . "/" . $w->getAttribute('path') );
			$this->NF = newframe(new tgc,$q,$m);
			return 3; }
		if( $end ) return 1;
		return 2;
	}
};

?>