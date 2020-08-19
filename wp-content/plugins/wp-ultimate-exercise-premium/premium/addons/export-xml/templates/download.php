<?php
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="WPUEP_Exercises.xml"');

$exportExercies = isset( $_POST['exportExercies'] ) ? base64_decode( $_POST['exportExercies'] ) : 'Exercise export failed.';
echo $exportExercies;