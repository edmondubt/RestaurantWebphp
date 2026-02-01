<?php
session_start();

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

  async function showCategory(name) {
    categories.className = "categories top";
    content.innerHTML = "";

    try {
      const res = await fetch("menu_api.php?category=" + encodeURIComponent(name));
      const list = await res.json();

      if (!list.length) {
        content.innerHTML = "<p style='color:white'>Ska produkte per kete kategori.</p>";
        return;
      }

      for (let i = 0; i < list.length; i++) {
        const img = list[i].image_path ? list[i].image_path : "Img/photo2.png";
        const desc = list[i].description ? list[i].description : "";
        const price = Number(list[i].price).toFixed(2) + "$";

        content.innerHTML +=
          '<div class="card">' +
            '<img src="' + img + '">' +
            '<div class="text">' +
              '<h4>' + escapeHtml(list[i].title) + '</h4>' +
              '<p>' + escapeHtml(desc) + ' <b>' + price + '</b></p>' +
            '</div>' +
          '</div>';
      }
    } catch (e) {
      content.innerHTML = "<p style='color:white'>Gabim ne marrjen e te dhenave.</p>";
    }
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll("&","&amp;")
      .replaceAll("<","&lt;")
      .replaceAll(">","&gt;")
      .replaceAll('"',"&quot;")
      .replaceAll("'","&#039;");
  }
</script>



</body>
</html>