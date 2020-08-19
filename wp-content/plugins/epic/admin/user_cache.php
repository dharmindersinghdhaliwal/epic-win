<h3><?php _e('Update User Search Cache','epic'); ?></h3>

<p><?php _e('In order to keep your search working smoothly you can update user cache from here. However it will be updated once user profile is updated.','epic'); ?></p>
<?php 
$users = array();
$users = get_users(array('fields'=>'ID'));
?>

<p>
<?php 
    echo sprintf(__('You have total <span id="epic-total-user" style="font-weight: bold;">%s</span> users in your website.', 'epic'), count($users));
?>
</p>

<p>
<?php 
    _e('<p id="epic-processing-tag" style="display:none;">Processing.... <span id="epic-completed-users" style="display:none;"> users Completed</span> </p>','epic');
?>
</p>
<p id="epic-upgrade-success" style="display:none;">
<span style="color: green; font-weight: bold;"><?php _e('User Search Cache Updated.','epic')?></span>
</p>