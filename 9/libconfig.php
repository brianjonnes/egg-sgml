<?php
# Copyright: GPL; Copyleft: Technews Publishing, South Africa.


class libconfig {
	public $self_href, $shipyard;
	public $sct; public $scriptnow;
	public $templatefile; public $urlpath;
	public $api, $nodoctype;
	public $interimjsupgrade;
	public $secrets_path;
	public $rootdoc, $file_ext, $altextension, $extoptional; 
	function __construct() {
		$this->sct = [ 'br' => 1, 'hr' => 1, 'img' => 1, 'meta' => 1, 'link' => 1, 'input' => 1, 'base' => 1 ];
		$this->scriptnow = time();
		$this->clips = [ ];
	}
	function write_end_of_tag( $q, $tag ) {
		if( array_key_exists( strtolower($tag), $this->sct ) ) {
			$q->write('/>');
		} else {
			$q->write('>');
		}
	}
	function write_close_tag( $q, $tag ) {
		if( ! array_key_exists( strtolower($tag), $this->sct ) ) {
			$q->write('</' . $tag . '>'); }
	}
	function to_file_number_one( $url, $query ) {
		if( $url == '/' ) {
			$url = $this->rootdoc; }
		$url = apply_relative_path( $_SERVER['DOCUMENT_ROOT'], $url );
		if( $this->file_ext != '' ) {
			if( file_exists( $url . $this->file_ext ) ) {
				return [ 200, $url . $this->file_ext ]; } }
		if( $this->altextension != '' ) {
			if( file_exists( $url . $this->altextension ) ) {
				return [ 200, $url . $this->altextension ]; } }
		if( $this->extoptional ) {
			if( file_exists( $url ) ) {
				return [ 200, $url ]; } }

		if( $this->fallback != '' ) {
			$c = apply_relative_path( $_SERVER['DOCUMENT_ROOT'], $env->fallback );
			if( $this->file_ext != '' ) {
				if( file_exists( $c . $this->file_ext ) ) {
					return [ 404, $c . $this->file_ext ]; } }
			if( $this->altextension != '' ) {
				if( file_exists( $c . $this->altextension ) ) {
					return [ 404, $c . $this->altextension ]; } }
			if( $this->extoptional ) {
				if( file_exists( $c ) ) {
					return [ 404, $c ]; } }
		}
		return [ 404 ];
	}

	function expanddirpath( $H ) {
		if( $H == '' ) {
			return $_SERVER['DOCUMENT_ROOT'] . '/'; }

		if( $H[strlen($H)-1] != '/' ) $H .= '/';
		if( $H[0] == '/' ) return $H;
		return $_SERVER['DOCUMENT_ROOT'] . '/' . $H;
	}
	function dirpath2web( $P, $H ) {
		$a = $_SERVER['DOCUMENT_ROOT'];
		if( $H == '' ) {
		} else if( $H[0] == '/' ) return $H;
		if( $P == $a ) return '/' . $H;
		if( substr($P,0,strlen($a)+1) != $a . '/' ) return $P;
		$a = substr($P,strlen($a));
		if( $H != '' ) if( $a[strlen($a)-1] != '/' ) $a .= '/' . $H;
		return $a;
	}
	function load_eggsgml_file( $path ) {
		if( ! file_exists( $path ) ) {
			$this->brian( 'not found: ' . $path );
			return; }
		return load_eggsgml_file( $path );
	}
	# I'm not about to take up knitting (so I can't stick to it),
	# and I am averse to the using of medical terminology.
	# Circuit tracing belongs to electronics.
	# A debugger is an imposing tool, and in truth unless we create
	# our own picture we're not likely to 
	# increase our understanding.
	#
	# If the code belongs to us, we simply need to have more information
	# about what it's doing in order to come to understand what it's
	# doing wrong.  Some call the art of correcting others, 
	# reductio ad absurdum, but the use of Latin lends itself to 
	# the creating of (absurd) pictures of Ancient Rome, which
	# is just about the opposite reason why Latin was the language
	# of the sciences.
	#
	# Encouraging someone to talk who might've drawn a wrong conclusion
	# somewhere, who had no way of knowing it, can only be done in
	# good faith--which carries the draw-back of allowing ourselves
	# to be persuaded not to argue.
	#
	# Don't talk, just fix!
	#
	# Commanding someone to talk to us is nonsensical, yet it
	# is natural. Imagining a ship can talk to us, we would only 
	# want it to keep us informed of things that need attending to, 
	# while it is in the shipyard. Obviously.
	#
	# And obviously they call us by name.
	#
	public $shipyard_log;
	function brian( $d ) {
		if( ! $this->shipyard ) return;
		if( $this->shipyard_log != '' ) {
			$this->shipyard_log .= ' / '; }
		$this->shipyard_log .= $d;
	}
	function _untested__write_prefixed_attributes( $q, $w, $f ) {
		$m = 00;
		for( $m = 0; $m < $w->attributes->length; $m += 1 ) {
			if( substr( $w->attributes->item($m)->name, 0, 2 ) == 'a-' ) {
				$s = substr( $w->attributes->item($m)->name, 2 );
				if( array_search( $s, $f ) === false ) {
					$q->write(' ' . $s);
					if( $w->attributes->item($m)->value != null ) {
						$q->write('="' . str_replace('"',"&quot;", str_replace( "&", "&amp;", $w->attributes->item($m)->value ) ) . '"' ); }
				}
			}
		}
	}
};

?>
