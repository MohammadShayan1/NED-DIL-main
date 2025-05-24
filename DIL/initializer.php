<?php
session_start();
require_once 'config.php';

// Redirect to home.php if not already on it
if (basename($_SERVER['PHP_SELF']) !== 'home.php') {
    header("Location: home.php");
    exit();
}
?>