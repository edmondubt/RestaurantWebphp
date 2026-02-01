<?php
session_start();
require_once "db.php";

$db = new Database();
$conn = $db->connect();


if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
  header('Content-Type: application/json; charset=utf-8');

  $catId = (int) ($_GET['cat'] ?? 0);

  if ($catId <= 0) {
    echo json_encode([]);
    exit;
  }

  try {
    $stmt = $conn->prepare("
            SELECT title, description, price, image_path
            FROM menu_items
            WHERE category_id = :cid
              AND (is_active = 1 OR is_active IS NULL)
            ORDER BY id DESC
        ");
    $stmt->execute(['cid' => $catId]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
  } catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([]);
  }
  exit;
}


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

function h($s)
{
  return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

    .nav-links li form {
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

    ul,
    ol {
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

    body {
      margin: 0;
      font-family: Arial;
      background: #111
    }

    .wrapper {
      min-height: 100vh;
      background: url('Img/vintage-old-rustic-cutlery-dark.jpg') center/cover no-repeat;
      display: flex;
      flex-direction: column;
      align-items: center
    }

    .categories {
      margin-top: 40vh;
      display: flex;
      gap: 20px;
      transition: .5s
    }

    .categories.top {
      margin-top: 100px
    }

    .category-btn {
      padding: 14px 26px;
      border: none;
      border-radius: 25px;
      background: rgb(106, 122, 2);
      color: #fff;
      font-size: 16px;
      cursor: pointer
    }

    .grid {
      width: 90%;
      max-width: 1200px;
      margin-top: 40px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px
    }

    @media(max-width:900px) {
      .grid {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media(max-width:500px) {
      .grid {
        grid-template-columns: 1fr
      }
    }

    .card {
      background: #fff;
      border-radius: 12px;
      overflow: hidden
    }

    .card img {
      width: 100%;
      height: 140px;
      object-fit: cover
    }

    .text {
      padding: 10px
    }

    .text h4 {
      margin: 0 0 6px;
      font-size: 15px
    }

    .text p {
      margin: 0;
      font-size: 13px;
      color: #555
    }

    .msg {
      color: white;
      margin-top: 20px
    }
  </style>
</head>

<body>

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
      <button class="category-btn" onclick="loadCategory(1)">Pije</button>
      <button class="category-btn" onclick="loadCategory(2)">Dessert</button>
      <button class="category-btn" onclick="loadCategory(3)">Ushqim</button>
    </div>

    <div id="content" class="grid"></div>
    <div id="msg" class="msg"></div>

  </div>

  <script>
    const categories = document.getElementById("categories");
    const content = document.getElementById("content");
    const msg = document.getElementById("msg");

    async function loadCategory(catId) {
      categories.classList.add("top");
      content.innerHTML = "";
      msg.innerHTML = "";

      try {
        const res = await fetch(`menu.php?ajax=1&cat=${catId}`);
        const data = await res.json();

        if (!data.length) {
          msg.innerText = "Ska produkte per kete kategori.";
          return;
        }

        data.forEach(p => {
          const img = p.image_path || "Img/photo2.png";
          content.innerHTML += `
        <div class="card">
          <img src="${img}">
          <div class="text">
            <h4>${escapeHtml(p.title)}</h4>
            <p>${escapeHtml(p.description || "")}
              <b>${Number(p.price).toFixed(2)}$</b>
            </p>
          </div>
        </div>
      `;
        });
      } catch (e) {
        msg.innerText = "Gabim ne marrjen e te dhenave.";
      }
    }

    function escapeHtml(str) {
      return String(str)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
    }
  </script>

</body>

</html>