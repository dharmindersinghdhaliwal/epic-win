<?php


$display = __('Someone (hopefully you) has used this email to register at {%blog_name%}','epic'). "\r\n\r\n";
$display .= __('Username:','epic'). " {%username%} \r\n\r\n";
$display .= __('Password:','epic'). " {%password%} \r\n\r\n";
$display .= __('Please click the link below to verify your ownership of this email:','epic') . "\r\n\r\n";
$display .= "{%activation_link%} \r\n\r\n";
$display .= __('Your account is pending admin approval. You will get an email notification once your account is approved.','epic'). " \r\n\r\n";
$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;
?>