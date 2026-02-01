

 <?php
session_start();
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<style>
    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
        background-image: url('/Img/background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-box {
        background: white;
        padding: 30px;
        border-radius: 12px;
        width: 320px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
        color: #555;
    }

    .input-group input {
        width: 94%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        transition: border 0.2s, box-shadow 0.2s;
    }

    .input-group input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102,126,234,0.2);
    }

    .login-box button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        background: linear-gradient(135deg, #0c0b09, #131212);
        border: none;
        color: white;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .login-box button:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }

    small {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #c0392b;
        min-height: 14px;
    }

    .hint {
        text-align: center;
        margin-top: 14px;
        font-size: 14px;
    }

    .msg {
        text-align: center;
        margin-bottom: 12px;
        font-size: 14px;
    }
</style>

<body>

<form id="registerForm" action="register_handler.php" method="POST" novalidate>
<div class="login-box">
    <h2>Create Account</h2>

    <?php if ($success === '1'): ?>
        <div class="msg" style="color: green;">Account u krijua me sukses! Tash kyçu.</div>
    <?php endif; ?>

    <?php if ($error === 'exists'): ?>
        <div class="msg" style="color: #c0392b;">Username ose email ekziston!</div>
    <?php elseif ($error === 'empty'): ?>
        <div class="msg" style="color: #c0392b;">Plotëso të gjitha fushat.</div>
    <?php elseif ($error === 'invalid_email'): ?>
        <div class="msg" style="color: #c0392b;">Email jo i vlefshëm.</div>
    <?php elseif ($error === 'server'): ?>
        <div class="msg" style="color: #c0392b;">Gabim në server. Provo prapë.</div>
    <?php endif; ?>

    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" id="username" placeholder="Enter username" autocomplete="username">
        <small id="usernameError"></small>
    </div>

    <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" id="email" placeholder="Enter email" autocomplete="email">
        <small id="emailError"></small>
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Enter password" autocomplete="new-password">
        <small id="passwordError"></small>
    </div>

    <div class="input-group">
        <label>Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm password" autocomplete="new-password">
        <small id="confirmError"></small>
    </div>

    <button type="submit" id="register-btn">Register</button>
    <p class="hint">Already have an account? <a href="login.php">Login</a></p>
</div>
</form>

<script>
    const form = document.getElementById('registerForm');

    form.addEventListener('submit', function (e) {
        document.getElementById('usernameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('confirmError').textContent = '';

        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let valid = true;

        if (username === '') {
            document.getElementById('usernameError').textContent = 'Ju lutem shtoni Username';
            valid = false;
        }

        if (email === '' || !emailPattern.test(email)) {
            document.getElementById('emailError').textContent = 'Email jo i vlefshëm';
            valid = false;
        }

        if (password === '') {
            document.getElementById('passwordError').textContent = 'Ju lutem shtoni Password';
            valid = false;
        } else if (password.length < 8) {
            document.getElementById('passwordError').textContent = 'Password duhet të ketë të paktën 8 karaktere';
            valid = false;
        }

        if (confirmPassword === '') {
            document.getElementById('confirmError').textContent = 'Ju lutem konfirmoni Password';
            valid = false;
        }

        if (password && confirmPassword && password !== confirmPassword) {
            document.getElementById('confirmError').textContent = 'Password dhe Confirm Password nuk përputhen';
            valid = false;
        }

        if (!valid) {
            e.preventDefault(); // ndalon submit te PHP
        }
        // nese valid -> e len formen me u dergu te register_handler.php (PA alert, PA redirect)
    });
</script>

</body>
</html>
