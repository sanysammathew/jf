<?php
if(!session_id()) {
    session_start();
}
require_once 'src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '414315435342591', // Replace {app-id} with your app id
  'app_secret' => '021b2f67c9bd2a250663f6db850d083d',
  'default_graph_version' => 'v2.4',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/facebook/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>