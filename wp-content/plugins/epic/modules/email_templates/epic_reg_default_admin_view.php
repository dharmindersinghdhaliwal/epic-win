<?php

$display = __('New user registration on ','epic'). " {%blog_name%}: \r\n\r\n";


$display .= __('Username:','epic'). " {%username%} \r\n\r\n";
$display .= __('E-mail:','epic'). " {%email%} \r\n\r\n";

$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;

?>