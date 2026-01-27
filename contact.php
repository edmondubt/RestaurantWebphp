<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="Contacts.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<style>
    body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    color: #333;
    background-image: url(Img/background.jpg);
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: static;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
}

a {
    text-decoration: none;
    color: #fff;
}
ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

header {
    padding: 10px 20px;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    
}

nav {
    padding: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: #fff;
    font-size: 39px;
    font-weight: 700;
}

.nav-links {
    display: flex;
    gap: 20px;
    margin-right: 60px;
}

.nav-links li a {
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    transition: background 0.3s;
}

.nav-links li a:hover {
    background: #ff6347;
}


.contact {
    padding: 100px 20px;
    text-align: center;
    
}

.contact h2 {
    margin-bottom: 20px;
    color: olive;
   
}

.contact-info {
    margin-bottom: 40px;
    color: antiquewhite;
}

.contact-info p {
    margin: 10px 0;
}

.contact-info a {
    color: #ff6347;
    text-decoration: none;
}

.contact-info a:hover {
    text-decoration: underline;
}

.map {
    text-align: center;
    color: olive;
}

.map iframe {
    border: 0;
    width: 100%;
    max-width: 900px;
    height: 450px;
}

footer {
    background: #333;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
}
.Img{
    margin-left: -10px;
}
h3{
    font-size: 27px;
}
@media (max-width: 725px) {
        .logo {
            font-size: 30px;
        }
    }
     @media (max-width: 669px) {
        .nav-links {
            gap: 0px;
        }
    }
       @media (max-width: 607px) {
        .nav-links {
            display: block;
        }
    }
</style>
<body>
<header>
<nav>
    <div class="logo">AP Restaurant</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="Menu.html">Menu</a></li>
        <li><a href="contact.php">Kontaktet</a></li>
        <li><a href="">Rezervimet</a></li>
    </ul>
</nav>
</header>

    <section class="contact">
       
        <h2>Na Kontaktoni</h2>
        <div class="contact-info">
            <p><strong>Instagram:</strong> <a href="https://www.instagram.com/aquaparkrestaurant?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">Aqua Park Instagram</a></p>
            <p><strong>Email:</strong> <a href="aquapark200@gmail.com">Aqua Park Gmail</a></p>
            <p><strong>Facebook:</strong> <a href="https://www.facebook.com/profile.php?id=100057507996781" target="_blank">Aqua Park Facebook</a></p>
            <p><strong>Numri Tel:</strong> <a href="tel:+38344121844">+383 44 121 844</a></p>
        </div>

        <div class="map">
            <h3>Lokacioni ynë</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2934.9650317399666!2d20.8522789154783!3d42.43399797918237!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13549db41c0ef9fb%3A0x1f4b0b1d1b9f7d9e!2sShtime!5e0!3m2!1sen!2s!4v1629735742231!5m2!1sen!2s"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"></iframe>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Aqua Park Restaurant. All rights reserved.</p>
    </footer>
</body>
</html>