<?php

#    chicken.php - Egg SGML nuts & bolts
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


class chicken {
	public $libconfig;
	public $stack, $repeat, $idents, $perfectbliss;
	public $diplomats;

	public $c;

	function __construct( $lc ) {
		$this->libconfig = $lc;
		$this->diplomats = [ [ ] ];
		$this->stack = null;
	}

	function talk_yourself_to_sleep() {
		$h = null; $d = $y = 0;
		while( $this->stack ) {
			$this->c = $this->stack;
			$d = $this->repeat;
			$y = $this->c->perfectbliss;
			$h = $this->c->idents;
			$this->repeat = $this->c->tsr;
			$this->stack = $this->c->tsq;
			if( $y ) array_shift( $this->diplomats );
			else if( $h ) $this->sane_unregister( $h );

			$this->c->tap( $this, $d );
		}
	}

	function diplomat( $m ) {
		if( ! array_key_exists( $m, $this->diplomats[0] ) ) return null;
		return $this->diplomats[0][$m][count($this->diplomats[0][$m])-1];
	}
	function sane_enqueue( $egg, $repeat ) {
		$egg->tsq = $this->stack;
		$egg->tsr = $this->repeat;
		$this->stack = $egg;
		$this->repeat = $repeat;
		if( $egg->perfectbliss ) { array_unshift( $this->diplomats, [ ] ); }
		if( $egg->idents ) $this->sane_register( $egg, $egg->idents );
	}
	function enqueue( $egg ) {
		$this->sane_enqueue( $egg, $this->c == $egg );
	}

	function write( $a ) {
		$this->diplomat('wall')->write($a);
	}
	function get_writer() {
		return $this->diplomat('wall'); }
	function write_doctype( $b ) {
		$this->diplomat('wall')->write('<!' . $b . '>');
	}
	function unhandled_tag( $m ) {
		echo( "unhandled tag". $m->nodeType. $m->nodeName );
	}

	function sane_register( $egg, $idents ) {
		$j = null;
		foreach( $idents as $j ) {
			$this->diplomats[0][$j][] = $egg; }
	}
	function sane_unregister( $idents ) {
		$j = null;
		foreach( $idents as $j ) {
			array_pop( $this->diplomats[0][$j] );
			if( count( $this->diplomats[0][$j] ) == 0 ) {
				unset( $this->diplomats[0][$j] ); }
		}
	}
};

?>
