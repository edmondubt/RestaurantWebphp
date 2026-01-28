<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Menu.css">
    <title>Menu</title>
</head>
<body>
    <style>

        body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }

    
    .wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.6s ease;
    }
    .wrapper {
    background-image: url('Img/vintage-old-rustic-cutlery-dark.jpg'); 
    background-size: cover;   
    background-position: center; 
    background-repeat: no-repeat; 
   
    }

    

    
    .categories {
      display: flex;
      gap: 20px;
      margin-top: 40vh;
      transition: all 0.6s ease;
    }

    .categories.top {
      margin-top: 95px;
    }

    .category-btn {
      padding: 14px 26px;
      border: none;
      border-radius: 25px;
      background: rgb(106, 122, 2);
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    .category-btn:hover {
      background: #ac5c01;
    }

   .grid {
  width: 90%;
  max-width: 1200px;
  margin-top: 40px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

@media (max-width: 900px) {
  .grid {
    grid-template-columns: repeat(2, 1fr); 
  }
}

@media (max-width: 500px) {
  .grid {
    grid-template-columns: 1fr; 
  }
}


    .card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
    }

    .text {
      padding: 10px;
    }

    .text h4 {
      margin: 0 0 6px;
      font-size: 15px;
    }

    .text p {
      margin: 0;
      font-size: 13px;
      color: #666;
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
    margin-right: 50px;
}

.nav-links li a {
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    transition: background 0.3s;
    text-decoration: none;
}

.nav-links li a:hover {
    background: #ff6347;
}

footer {
  width: 100%;         
  background-color: #222; 
  color: white;
  text-align: center;
  padding: 20px 0;
  margin-top: auto;
}
ul, ol {
  list-style: none;
  padding: 0;
  margin: 0;
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

    <header style="margin: 7px;">
        <nav> 
            <div class="logo">AP Restaurant</div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontaktet</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>


  <div class="wrapper">
    <div id="categories" class="categories">
      <button class="category-btn" onclick="showCategory('pije')">Pije</button>
      <button class="category-btn" onclick="showCategory('dessert')">Dessert</button>
      <button class="category-btn" onclick="showCategory('ushqim')">Ushqim</button>
    </div>

    <div id="content" class="grid"></div>

<script src="Menu.js"></script>
<footer>
        <p>&copy; 2024 Restaurant Aqua Park. All rights reserved.</p>
    </footer>

    <script>

        var categories = document.getElementById("categories");
  var content = document.getElementById("content");

  
  var pije = [
    { title: "Margarita", desc: "6.99$", img: "Img/photo2.png" },
    { title: "Whiskey Sour", desc: "5.99$", img: "Img/photo3.png" },
    { title: "Martini", desc: "7.99$", img: "Img/photo4.png" },
    { title: "Negroni", desc: "6.99$", img: "Img/photo5.png" },
    { title: "Aperol Spritz", desc: "5.99$", img: "Img/photo6.png" },
    { title: "Mojito", desc: "5.99$", img: "Img/photo7.png" },
    { title: "Espresso Martini", desc: "7.99%", img: "Img/photo8.png" },
    { title: "Cosmopolitan", desc: "6.99$", img: "Img/photo9.png" },
    { title: "French 75", desc: "8.99$", img: "Img/photo10.png" },
    { title: "Chocolate Old Fashioned", desc: "7.99$", img: "Img/photo11.png" }
  ];

  var dessert = [
    { title: "Crème Brûlée", desc: "7.99$", img: "Img/d1.png" },
    { title: "Mochi", desc: "5.99", img: "Img/d2.png" },
    { title: "Tiramisu", desc: "4.99$", img: "Img/d3.png" },
    { title: "Apple Pie", desc: "4.99$", img: "Img/d4.png" },
    { title: "Brownies", desc: "3.99$", img: "Img/d5.png" },
    { title: "Nanaimo Bar", desc: "5.99$", img: "Img/d6.png" },
    { title: "Churros", desc: "6.99$", img: "Img/d7.png" },
    { title: "Cheesecake", desc: "5.99$", img: "Img/d8.png" },
    { title: "Ice Cream", desc: "3.99$", img: "Img/d9.png" },
    { title: "Gulab Jamun", desc: "6.99", img: "Img/d10.png" }
  ];

  var ushqim = [
    { title: "Crisp Paupiette", desc: "28.99$", img: "Img/u1.png" },
    { title: "Char in Beeswax", desc: "20.99$", img: "Img/u2.png" },
    { title: "Artichoke Tart", desc: "24.99$", img: "Img/u3.png" },
    { title: "Lobster Bay Caviar", desc: "32.99$", img: "Img/u4.png" },
    { title: "Sushi Omakase", desc: "36.99$", img: "Img/u5.png" },
    { title: "Parmigiano Reggiano", desc: "27.99$", img: "Img/u6.png" },
    { title: "Millefeuille with Eel", desc: "42.99$", img: "Img/u7.png" },
    { title: "Roasted Sladesdown Duck", desc: "40.99$", img: "Img/u8.png" },
    { title: "Crayfish Tail Tartare", desc: "37.99$", img: "Img/u9.png" },
    { title: "Potato and Roe", desc: "33.99$", img: "Img/u10.png" }
  ];

  
  function showCategory(name) {
    categories.className = "categories top";
    content.innerHTML = "";

    var list;

    if (name === "pije") {
      list = pije;
    }

    if (name === "dessert") {
      list = dessert;
    }

    if (name === "ushqim") {
      list = ushqim;
    }

    for (var i = 0; i < list.length; i++) {
      content.innerHTML +=
        '<div class="card">' +
          '<img src="' + list[i].img + '">' +
          '<div class="text">' +
            '<h4>' + list[i].title + '</h4>' +
            '<p>' + list[i].desc + '</p>' +
          '</div>' +
        '</div>';
    }

    
  }

    </script>
   



</body>
</html>