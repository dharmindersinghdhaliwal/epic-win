<?php 

$display = __('Someone requested that the password be reset for the following account:','epic') . "\r\n\r\n";
$display .= "{%network_home_url%} \r\n\r\n";
$display .= __('Username:','epic') . " {%username%} \r\n\r\n";
$display .= __('If this was a mistake, just ignore this email and nothing will happen.','epic') . "\r\n\r\n";
$display .= __('To reset your password, visit the following address:','epic') . "\r\n\r\n";
$display .= "{%reset_page_url%} ";

$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;