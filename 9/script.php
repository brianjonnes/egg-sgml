<?php
#    script.php - Egg SGML nuts & bolts
#    Copyright 2020, 2021, 2022 Brian Jonnes
#
#    Egg-SGML is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, version 3 of the License.
#
#    Egg-SGML is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with Egg-SGML.  If not, see <https://www.gnu.org/licenses/>.

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

