<?php

$display  = "{%full_name%} ".__('has updated profile information. ','epic') . "\r\n\r\n";
        
$display .= __('Please find the updated information below','epic').  "\r\n\r\n";
$display .= "{%changed_fields%} \r\n";

$display .= __('Thanks','epic') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;
?>