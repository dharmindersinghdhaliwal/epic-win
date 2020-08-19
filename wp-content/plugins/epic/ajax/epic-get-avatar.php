<?php

/* include WordPress */
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $epic;

$email = $_GET['email'];

echo $epic->pic($email, 50);