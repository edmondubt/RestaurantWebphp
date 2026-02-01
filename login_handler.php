<?php
session_start();
require_once 'user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        header('Location: login.php?error=empty');
        exit;
    }

    $userObj = new User();
    $user = $userObj->login($username, $password);

    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

       if ($user['role'] === 'admin') {
        header("Location: admin_panel.php");
    } else {
        header("Location: home.php");
    }
    exit;

    } else {
        header('Location: login.php?error=invalid');
        exit;
    }

} else {
  
    header('Location: login.php');
    exit;
}