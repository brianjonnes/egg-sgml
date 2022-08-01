<?php
# Copyright: GPL; Copyleft: Technews Publishing, South Africa.

class script {
	public $ts;
	public $idents = [ ], $perfectbliss = 0;
	public $dn, $path;
	function __construct ($dn, $path) {
		$this->dn = $dn;
		$this->path = $path;
	}
	function tap( $env, $str ) {
		$w1 = null;
		if( $str ) {
			return; }

		$env->write('<' . $this->dn->nodeName);
		write_attributes( $env, $this->dn, [ ] );
		$env->write('>');
		$w1 = $this->dn->firstChild;
		while( $w1 != null ) {
			if( perfect_script($w1) ) $env->write( $w1->data ); $w1 = $w1->nextSibling; }
		$env->write('</script>');
	}
};

