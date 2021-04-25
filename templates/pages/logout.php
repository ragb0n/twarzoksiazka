<?php
$_SESSION = array();
session_destroy();
header("Location: /?action=login");
exit;
?>