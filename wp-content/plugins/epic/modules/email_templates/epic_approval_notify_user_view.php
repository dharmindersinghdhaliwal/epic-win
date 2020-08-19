<?php

$display  = __('Your account has been approved successfully. ','epic') . "\r\n\r\n";
        
$display .= __('Username:','epic').  " {%username%} \r\n\r\n";
$display .= __('E-mail:','epic'). " {%email%} \r\n";

$display .= __('You can now log in to use your account using the following link.','epic') . "\r\n\r\n";
$display .= " {%login_link%} \r\n\r\n";
$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;

?>