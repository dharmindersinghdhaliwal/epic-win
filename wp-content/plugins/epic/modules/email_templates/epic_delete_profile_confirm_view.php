<?php

$display  = __('Someone (hopefully you) has requested to delete your profile at {%blog_name%}','epic'). "\r\n\r\n";
$display .= __('Username:','epic'). " {%username%} \r\n\r\n";
$display .= __('E-mail:','epic'). " {%email%} \r\n\r\n";

$display .= __('Please click the link below to confirm the removal of profile:','epic') . "\r\n\r\n";
$display .= "{%profile_delete_confirm_link%} \r\n\r\n";
$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;

?>