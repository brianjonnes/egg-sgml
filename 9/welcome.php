<?php

#    welcome.php - Egg SGML nuts & bolts
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

class welcome {
	public $ts;

	public $idents = [ 'wall', 'red' ];

	function write($q) {
	}

	function tap($chicken,$str) {
		$chicken->enqueue($this);
		$chicken->enqueue($casserole);
	}
};
