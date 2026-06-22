<?php
require_once __DIR__ . '/../includes/functions.php';
session_regenerate_id(true);
unset($_SESSION['user']);
flash('success', 'You have been logged out.');
redirect('index.php');
