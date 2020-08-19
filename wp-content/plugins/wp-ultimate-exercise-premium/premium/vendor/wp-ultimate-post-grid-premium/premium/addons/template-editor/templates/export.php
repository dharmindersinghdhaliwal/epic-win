<?php
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=WPUPG_Etemplate.txt");

$exportTemplate = isset( $_POST['exportTemplate'] ) ? $_POST['exportTemplate'] : 'Template export failed.';
echo $exportTemplate;