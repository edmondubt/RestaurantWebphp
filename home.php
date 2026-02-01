<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: #333;
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
            font-size: bold;
        }

        nav {
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
            margin-right: 20px;
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

        .hero {
            /* background-image: url('/Img/BEST.jpg');   */
            /* width: 100%; */
            background-size: cover;
            background-position: center;
            /* height: 100vh; */
            /* display: flex; */
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
        }

        .hero-content {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .btn {
            background: #ff6347;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;

        }

        .btn:hover {
            background: #b80101;
        }

        section {
            /* padding: 60px 20px; */
            text-align: center;
        }

        .about,
        .contact,
        .reservations {
            background: #202020;
            color: antiquewhite;
        }

        #slideshow {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }


        footer {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .IMG {
            position: absolute;
            bottom: 500px;
            right: 50%;
            transform: translateX(50%);
            margin-bottom: -45px;
        }



        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #000;
        }


        .slider {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }


        #slideshow {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }


        .nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 26px;
            cursor: pointer;
            z-index: 10;
            transition: 0.3s;
        }

        .logout-btn {
            background: transparent;
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
            cursor: pointer;
            font: inherit;
            line-height: normal;
        }
        .nav-links li form{
    margin: 0;
    padding: 0;
    display: inline;
}

        .logout-btn:hover {
            background: #ff6347;
        }

        .nav:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .about-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            gap: 40px;
            background-color: #fff;
        }

        .about-text {
            flex: 1;
        }


        .about-text p {
            font-size: 20px;
            line-height: 1.6;
        }

        .about-image {
            flex: 1;
        }

        .about-image img {
            width: 100%;
            height: auto;

            object-fit: cover;

        }

        @media (max-width: 768px) {
            .about-section {
                flex-direction: column;
            }
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
    <header>
        <nav>
            <div class="logo">AP Restaurant</div>
            <ul class="nav-links">
               <li><button class="logout-btn" onclick="window.location.href='home.php'">Home</button></li>
                <li><button class="logout-btn" onclick="window.location.href='menu.php'">Menu</button></li>
                <li><button class="logout-btn" onclick="window.location.href='contact.php'">Kontaktet</button></li>

                <li>
                    <form action="logout.php" method="post" style="display:inline;">
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>



    <div class="slider">
        <img id="slideshow" />

        <button class="nav prev" onclick="backImg()">‹</button>
        <button class="nav next" onclick="nextImg()">›</button>
    </div>

    <script src="index.js"></script>




    <section class="about-section">
        <div class="about-text">

            <p>Vendi ynë është ambienti më i mirë për familjen tuaj. Këtu mund të gjeni rehati, ushqim të shijshëm dhe
                eksperiencë të paharrueshme për çdo vizitë.</p>
        </div>
        <div class="about-image">
            <img src="./Img/logo.png" alt="Restaurant Image">
        </div>
    </section>




    <footer>
        <p>&copy; 2024 Restaurant Aqua Park. All rights reserved.</p>
    </footer>
    <script>
        let i = 0;
        let interval;

        let imgArray = [

            'Img/BEST.jpg',
            'Img/Img3.jpg',
            'Img/Img4.webp',
            'Img/Img5.jpg',
            'Img/Img6.jpg',


        ];
        console.log(imgArray);

        function shfaqImg() {
            document.getElementById('slideshow').src = imgArray[i];
        }

        function nextImg() {
            i++;
            if (i >= imgArray.length) i = 0;
            shfaqImg();
            resetInterval();
        }

        function backImg() {
            i--;
            if (i < 0) i = imgArray.length - 1;
            shfaqImg();
            resetInterval();
        }

        function startSlider() {
            interval = setInterval(() => {
                nextImgAuto();
            }, 2600);
        }

        function nextImgAuto() {
            i++;
            if (i >= imgArray.length) i = 0;
            shfaqImg();
        }

        function resetInterval() {
            clearInterval(interval);
            startSlider();
        }

        window.addEventListener('load', () => {
            shfaqImg();
            startSlider();
        });

    </script>
</body>

</html>