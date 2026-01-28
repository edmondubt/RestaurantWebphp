<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
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
</style>
<body>
    <div class="login-box">
        <h2>Login</h2>

        <div class="input-group">
            <label>Username</label>
            <input type="text"  id="username" placeholder="Enter username">
             <small id="usernameError"></small>
        </div>




        <div class="input-group">
            <label>Password</label>
            <input type="password"  id="password" placeholder="Enter password">
            <small id="passwordError"></small>

        </div>



        <button id="login-btn">Login</button>
        <p class="hint">Dont have an account? <a href="register.php">Register</a></p>
        <script>
            document.getElementById('login-btn').addEventListener('click', function () {


    document.getElementById('usernameError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    let valid = true;

    if (username === '') {
        document.getElementById('usernameError').textContent = 'Ju lutem shtoni Username';
        valid = false;

    }
    if (password === '') {
        document.getElementById('passwordError').textContent = 'Ju lutem shtoni Password';
        valid = false;
    }
    else if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password duhet të ketë të paktën 8 karaktere';
        valid = false;
    }
    if (valid) {
        alert('Login me sukses!');
        window.location.href = 'home.php';
    }
});

        </script>
    </div>
</body>

</html>