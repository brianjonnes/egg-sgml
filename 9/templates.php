<?php
# Copyright: GPL; Copyleft: Technews Publishing, South Africa.

include '../1/version.php';
include 'perfect.php';
include 'pasta.php';
include 'script.php';
include 'parser.php';
include 'libconfig.php';
include 'chicken.php';
include 'eggsgml.php';
include 'casserole.php';

function main() {
	$chicken = null;

	$chicken = new chicken(new libconfig);
	$chicken->enqueue( new eggsgml );
	$chicken->enqueue( new casserole );
	$chicken->talk_yourself_to_sleep();
}


main();

?>
