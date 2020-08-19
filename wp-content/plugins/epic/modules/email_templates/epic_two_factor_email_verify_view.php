<?php


$display = __('Someone (hopefully you) has used this email to verify the login at {%blog_name%}','epic'). "\r\n\r\n";
$display .= __('Username:','epic'). " {%username%} \r\n\r\n";
$display .= __('Email:','epic'). " {%email%} \r\n\r\n";
$display .= __('Please click the link below to login automatically:','epic') . "\r\n\r\n";
$display .= "{%email_two_factor_login_link%} \r\n\r\n";


$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;
?>