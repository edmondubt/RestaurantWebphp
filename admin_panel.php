<?php
require_once "admin_guard.php";
require_once "db.php";

$conn = (new Database())->connect();
function h($s)
{
  return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION["flash"]))
  $_SESSION["flash"] = "";

function flash($msg)
{
  $_SESSION["flash"] = $msg;
  header("Location: admin_panel.php");
  exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
  $cid = (int) ($_POST["category_id"] ?? 0);
  $t = trim($_POST["title"] ?? "");
  $p = (float) ($_POST["price"] ?? 0);
  $d = trim($_POST["description"] ?? "");

  if ($cid <= 0 || $t === "" || $p <= 0)
    flash(" Plotëso Category / Title / Price");

  $st = $conn->prepare("INSERT INTO menu_items(category_id,title,price,description,is_active)
                        VALUES(:c,:t,:p,:d,1)");
  $ok = $st->execute([":c" => $cid, ":t" => $t, ":p" => $p, ":d" => ($d === "" ? null : $d)]);
  flash($ok ? " U shtua" : " Gabim");
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
  $id = (int) ($_POST["id"] ?? 0);
  $cid = (int) ($_POST["category_id"] ?? 0);
  $t = trim($_POST["title"] ?? "");
  $p = (float) ($_POST["price"] ?? 0);
  $d = trim($_POST["description"] ?? "");

  if ($id <= 0 || $cid <= 0 || $t === "" || $p <= 0)
    flash(" Ploteso fushat ");

  $st = $conn->prepare("UPDATE menu_items
                        SET category_id=:c,title=:t,price=:p,description=:d
                        WHERE id=:id");
  $ok = $st->execute([":c" => $cid, ":t" => $t, ":p" => $p, ":d" => ($d === "" ? null : $d), ":id" => $id]);
  flash($ok ? " U perditesua" : " Gabim");
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["del"])) {
  $id = (int) ($_POST["id"] ?? 0);
  if ($id <= 0)
    flash(" ID jo valid");

  $st = $conn->prepare("DELETE FROM menu_items WHERE id=:id");
  $ok = $st->execute([":id" => $id]);
  flash($ok ? " U fshi" : " Gabim");
}


$categories = $conn->query("SELECT id,name FROM menu_categories ORDER BY name")
  ->fetchAll(PDO::FETCH_ASSOC);

$items = $conn->query("SELECT mi.id, mi.category_id, mi.title, mi.price, mi.description, mi.created_at,
                              mc.name AS category_name
                       FROM menu_items mi
                       JOIN menu_categories mc ON mc.id=mi.category_id
                       ORDER BY mi.id DESC")
  ->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION["flash"];
$_SESSION["flash"] = "";
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Menu</title>
  <style>
    body {
      font-family: Arial;
      margin: 0;
      background: #f6f7fb
    }

    .wrap {
      max-width: 1100px;
      margin: 20px auto;
      padding: 0 14px
    }

    .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px
    }

    .card {
      background: #fff;
      border: 1px solid #e6e7ee;
      border-radius: 10px;
      padding: 14px;
      margin-top: 14px
    }

    .msg {
      padding: 10px;
      border-radius: 10px;
      background: #111;
      color: #fff;
      margin-top: 14px
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #cfd3e1;
      border-radius: 8px
    }

    textarea {
      min-height: 70px;
      resize: vertical
    }

    .row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px
    }

    @media(max-width:760px) {
      .row {
        grid-template-columns: 1fr
      }
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px
    }

    th,
    td {
      border-bottom: 1px solid #eee;
      padding: 10px;
      vertical-align: top;
      font-size: 13px
    }

    th {
      background: #fafafa;
      text-align: left
    }

    .btn {
      padding: 8px 12px;
      border: 0;
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer
    }

    .btn.blue {
      background: #2563eb;
      color: #fff
    }

    .btn.red {
      background: #ef4444;
      color: #fff
    }

    .btn.gray {
      background: #e5e7eb
    }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }
  </style>
</head>

<body>
  <div class="wrap">

    <div class="top">
      <h2 style="margin:0">Admin Panel (Menu)</h2>
      <form action="logout.php" method="post" style="margin:0">
        <button class="btn red" type="submit">Logout</button>
      </form>
    </div>

    <?php if ($flash !== ""): ?>
      <div class="msg"><?= h($flash) ?></div>
    <?php endif; ?>

    <div class="card">
      <h3 style="margin:0 0 10px">Shto Produkt</h3>
      <form method="post" class="row" style="align-items:end">
        <div>
          <label>Category</label>
          <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
              <option value="<?= (int) $c["id"] ?>"><?= h($c["name"]) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Price</label>
          <input name="price" type="number" step="0.01" required>
        </div>
        <div style="grid-column:1/-1">
          <label>Title</label>
          <input name="title" required>
        </div>
        <div style="grid-column:1/-1">
          <label>Description (optional)</label>
          <textarea name="description"></textarea>
        </div>
        <div style="grid-column:1/-1">
          <button class="btn blue" name="add" type="submit">Add</button>
        </div>
      </form>
    </div>

    <div class="card">
      <h3 style="margin:0 0 10px">Produktet</h3>

      <table>
        <thead>
          <tr>
            <th style="width:60px">ID</th>
            <th style="width:160px">Category</th>
            <th>Title</th>
            <th style="width:110px">Price</th>
            <th>Description</th>
            <th style="width:160px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <form method="post">
                <td>
                  <?= (int) $it["id"] ?>
                  <input type="hidden" name="id" value="<?= (int) $it["id"] ?>">
                </td>
                <td>
                  <select name="category_id">
                    <?php foreach ($categories as $c): ?>
                      <option value="<?= (int) $c["id"] ?>" <?= ((int) $c["id"] === (int) $it["category_id"]) ? "selected" : "" ?>>
                        <?= h($c["name"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input name="title" value="<?= h($it["title"]) ?>"></td>
                <td><input name="price" type="number" step="0.01"
                    value="<?= h(number_format((float) $it["price"], 2, '.', '')) ?>"></td>
                <td><textarea name="description"><?= h($it["description"] ?? "") ?></textarea></td>
                <td>
                  <div class="actions">
                    <button class="btn blue" name="save" type="submit">Save</button>
              </form>

              <form method="post" onsubmit="return confirm('Me fshi kete produkt?');">
                <input type="hidden" name="id" value="<?= (int) $it["id"] ?>">
                <button class="btn red" name="del" type="submit">Delete</button>
              </form>
      </div>
      </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    </table>

  </div>

  </div>
</body>

</html>