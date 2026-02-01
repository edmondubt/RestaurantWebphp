<?php
require_once "admin_guard.php";
require_once "db.php";

$db = new Database();
$conn = $db->connect();

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

if (!isset($_SESSION["flash"])) $_SESSION["flash"] = "";

/* =========================
   USERS: Create
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_user"])) {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $role     = $_POST["role"] ?? "user";

    if ($username === "" || $email === "" || $password === "") {
        $_SESSION["flash"] = "❌ Plotëso të gjitha fushat te Users.";
    } elseif (!in_array($role, ["admin","user"], true)) {
        $_SESSION["flash"] = "❌ Role jo valid.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = :u OR email = :e");
        $check->execute([":u"=>$username, ":e"=>$email]);

        if ($check->rowCount() > 0) {
            $_SESSION["flash"] = "❌ Username ose Email ekziston.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:u,:e,:p,:r)");
            $ok = $stmt->execute([":u"=>$username, ":e"=>$email, ":p"=>$password, ":r"=>$role]);
            $_SESSION["flash"] = $ok ? "✅ User u krijua me sukses!" : "❌ Gabim gjatë krijimit të user-it.";
        }
    }

    header("Location: admin_panel.php#users");
    exit;
}

/* =========================
   USERS: Delete
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {
    $delete_id = (int)($_POST["delete_id"] ?? 0);

    if ($delete_id === (int)($_SESSION["user_id"] ?? 0)) {
        $_SESSION["flash"] = "❌ S'mundesh me fshi vetveten.";
    } elseif ($delete_id > 0) {
        $del = $conn->prepare("DELETE FROM users WHERE id = :id");
        $ok = $del->execute([":id"=>$delete_id]);
        $_SESSION["flash"] = $ok ? "✅ User u fshi!" : "❌ Gabim gjatë fshirjes së user-it.";
    }

    header("Location: admin_panel.php#users");
    exit;
}

/* =========================
   USERS: Edit (username/email/role + password optional)
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_user"])) {
    $id       = (int)($_POST["edit_user_id"] ?? 0);
    $username = trim($_POST["edit_username"] ?? "");
    $email    = trim($_POST["edit_email"] ?? "");
    $role     = $_POST["edit_role"] ?? "user";
    $password = trim($_POST["edit_password"] ?? ""); // optional

    if ($id <= 0 || $username === "" || $email === "") {
        $_SESSION["flash"] = "❌ Username/Email s'munden me qenë bosh.";
        header("Location: admin_panel.php#users");
        exit;
    }
    if (!in_array($role, ["admin","user"], true)) {
        $_SESSION["flash"] = "❌ Role jo valid.";
        header("Location: admin_panel.php#users");
        exit;
    }

    // mos lejo me e ul role-in e vetes, nese don (opsionale)
    // (po ta lë të lirë — mundesh me e bo admin tjetër)

    // check username/email unique (për user tjetër)
    $check = $conn->prepare("SELECT id FROM users WHERE (username = :u OR email = :e) AND id <> :id");
    $check->execute([":u"=>$username, ":e"=>$email, ":id"=>$id]);
    if ($check->rowCount() > 0) {
        $_SESSION["flash"] = "❌ Username ose Email ekziston te një user tjetër.";
        header("Location: admin_panel.php#users");
        exit;
    }

    if ($password !== "") {
        $stmt = $conn->prepare("UPDATE users SET username=:u, email=:e, role=:r, password=:p WHERE id=:id");
        $ok = $stmt->execute([":u"=>$username, ":e"=>$email, ":r"=>$role, ":p"=>$password, ":id"=>$id]);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=:u, email=:e, role=:r WHERE id=:id");
        $ok = $stmt->execute([":u"=>$username, ":e"=>$email, ":r"=>$role, ":id"=>$id]);
    }

    $_SESSION["flash"] = $ok ? "✅ User u përditësua!" : "❌ Gabim gjatë editimit të user-it.";
    header("Location: admin_panel.php#users");
    exit;
}

/* =========================
   MENU: Add + Upload
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_item"])) {
    $category_id = (int)($_POST["category_id"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $description = trim($_POST["description"] ?? "");

    $image_path = null;

    if ($category_id <= 0 || $title === "" || $price === "") {
        $_SESSION["flash"] = "❌ Te Menu: Category, Title dhe Price janë të detyrueshme.";
        header("Location: admin_panel.php#menu");
        exit;
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
            $_SESSION["flash"] = "❌ Gabim gjatë upload-it të fotos.";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $allowed = ["image/jpeg"=>"jpg","image/png"=>"png","image/webp"=>"webp"];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($_FILES["image"]["tmp_name"]);

        if (!isset($allowed[$mime])) {
            $_SESSION["flash"] = "❌ Foto jo valide (JPG/PNG/WEBP).";
            header("Location: admin_panel.php#menu");
            exit;
        }
        if ($_FILES["image"]["size"] > 2*1024*1024) {
            $_SESSION["flash"] = "❌ Foto shumë e madhe (max 2MB).";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $ext = $allowed[$mime];
        $filename = bin2hex(random_bytes(16)).".".$ext;

        $uploadDir = __DIR__ . "/uploads/menu/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $dest = $uploadDir.$filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
            $_SESSION["flash"] = "❌ S’u arrit me ruajt foton.";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $image_path = "uploads/menu/".$filename;
    }

    $stmt = $conn->prepare(
        "INSERT INTO menu_items (category_id, title, price, description, image_path, is_active)
         VALUES (:cid,:t,:p,:d,:img,1)"
    );
    $ok = $stmt->execute([
        ":cid"=>$category_id,
        ":t"=>$title,
        ":p"=>$price,
        ":d"=>($description===""?null:$description),
        ":img"=>$image_path
    ]);

    $_SESSION["flash"] = $ok ? "✅ Produkti u shtua!" : "❌ Gabim gjatë shtimit të produktit.";
    header("Location: admin_panel.php#menu");
    exit;
}

/* =========================
   MENU: Delete + delete image
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_item"])) {
    $id = (int)($_POST["item_id"] ?? 0);

    if ($id > 0) {
        $get = $conn->prepare("SELECT image_path FROM menu_items WHERE id = :id");
        $get->execute([":id"=>$id]);
        $row = $get->fetch(PDO::FETCH_ASSOC);

        $del = $conn->prepare("DELETE FROM menu_items WHERE id = :id");
        $ok = $del->execute([":id"=>$id]);

        if ($ok && $row && !empty($row["image_path"])) {
            $file = __DIR__ . "/" . $row["image_path"];
            if (is_file($file)) @unlink($file);
        }

        $_SESSION["flash"] = $ok ? "✅ Produkti u fshi!" : "❌ Gabim gjatë fshirjes së produktit.";
    }

    header("Location: admin_panel.php#menu");
    exit;
}

/* =========================
   MENU: Edit + optional new image (delete old)
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_item"])) {
    $id = (int)($_POST["edit_item_id"] ?? 0);
    $category_id = (int)($_POST["edit_category_id"] ?? 0);
    $title = trim($_POST["edit_title"] ?? "");
    $price = trim($_POST["edit_price"] ?? "");
    $description = trim($_POST["edit_description"] ?? "");

    if ($id<=0 || $category_id<=0 || $title==="" || $price==="") {
        $_SESSION["flash"] = "❌ Te Edit Menu: plotëso fushat.";
        header("Location: admin_panel.php#menu");
        exit;
    }

    // get old image
    $old = $conn->prepare("SELECT image_path FROM menu_items WHERE id=:id");
    $old->execute([":id"=>$id]);
    $oldRow = $old->fetch(PDO::FETCH_ASSOC);
    $oldPath = $oldRow["image_path"] ?? null;

    $newImagePath = $oldPath;

    // if new image uploaded => upload and replace
    if (isset($_FILES["edit_image"]) && $_FILES["edit_image"]["error"] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES["edit_image"]["error"] !== UPLOAD_ERR_OK) {
            $_SESSION["flash"] = "❌ Gabim gjatë upload-it të fotos (edit).";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $allowed = ["image/jpeg"=>"jpg","image/png"=>"png","image/webp"=>"webp"];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($_FILES["edit_image"]["tmp_name"]);

        if (!isset($allowed[$mime])) {
            $_SESSION["flash"] = "❌ Foto jo valide (edit) (JPG/PNG/WEBP).";
            header("Location: admin_panel.php#menu");
            exit;
        }
        if ($_FILES["edit_image"]["size"] > 2*1024*1024) {
            $_SESSION["flash"] = "❌ Foto shumë e madhe (edit) (max 2MB).";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $ext = $allowed[$mime];
        $filename = bin2hex(random_bytes(16)).".".$ext;

        $uploadDir = __DIR__ . "/uploads/menu/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $dest = $uploadDir.$filename;
        if (!move_uploaded_file($_FILES["edit_image"]["tmp_name"], $dest)) {
            $_SESSION["flash"] = "❌ S’u arrit me ruajt foton (edit).";
            header("Location: admin_panel.php#menu");
            exit;
        }

        $newImagePath = "uploads/menu/".$filename;

        // delete old file
        if (!empty($oldPath)) {
            $file = __DIR__ . "/" . $oldPath;
            if (is_file($file)) @unlink($file);
        }
    }

    $stmt = $conn->prepare(
        "UPDATE menu_items
         SET category_id=:cid, title=:t, price=:p, description=:d, image_path=:img
         WHERE id=:id"
    );
    $ok = $stmt->execute([
        ":cid"=>$category_id,
        ":t"=>$title,
        ":p"=>$price,
        ":d"=>($description===""?null:$description),
        ":img"=>$newImagePath,
        ":id"=>$id
    ]);

    $_SESSION["flash"] = $ok ? "✅ Produkti u përditësua!" : "❌ Gabim gjatë editimit të produktit.";
    header("Location: admin_panel.php#menu");
    exit;
}

/* =========================
   Fetch data
========================= */
$users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC")
             ->fetchAll(PDO::FETCH_ASSOC);

$categories = $conn->query("SELECT id, slug, name FROM menu_categories ORDER BY id")
                  ->fetchAll(PDO::FETCH_ASSOC);

$menuFilter = $_GET["cat"] ?? "all";
$params = [];
$where = "";
if ($menuFilter !== "all") {
    $where = "WHERE mc.slug = :slug";
    $params[":slug"] = $menuFilter;
}

$sql = "SELECT mi.id, mc.name AS category, mc.slug, mi.title, mi.price, mi.description, mi.image_path, mi.created_at, mi.category_id
        FROM menu_items mi
        JOIN menu_categories mc ON mc.id = mi.category_id
        $where
        ORDER BY mi.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION["flash"] ?? "";
$_SESSION["flash"] = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Panel</title>
<style>
  *{box-sizing:border-box}
  body{margin:0;font-family:Arial,sans-serif;background:#f1f3f6;color:#111}
  .layout{display:flex;min-height:100vh}
  .sidebar{width:240px;background:#0f172a;color:#fff;display:flex;flex-direction:column;padding:18px 14px;position:sticky;top:0;height:100vh}
  .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:18px;margin-bottom:18px}
  .brand .dot{width:10px;height:10px;border-radius:50%;background:#60a5fa;display:inline-block}
  .nav{display:flex;flex-direction:column;gap:6px}
  .nav button{width:100%;border:none;background:transparent;color:#cbd5e1;text-align:left;padding:10px 12px;border-radius:10px;cursor:pointer;font-size:14px;display:flex;align-items:center;gap:10px}
  .nav button:hover{background:#111c3a;color:#fff}
  .nav button.active{background:#1d4ed8;color:#fff}
  .sidebar .spacer{flex:1}
  .main{flex:1;padding:18px 22px}
  .content{margin-top:10px}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;box-shadow:0 6px 18px rgba(0,0,0,.05)}
  .grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  @media(max-width:950px){.grid2{grid-template-columns:1fr}}
  .title{margin:0 0 10px;font-size:18px}
  .sub{margin:0 0 10px;color:#6b7280;font-size:13px}
  .msg{margin:12px 0;background:#111827;color:#fff;padding:10px 12px;border-radius:12px}
  label{font-size:12px;color:#374151}
  input,select,textarea{width:100%;padding:10px 12px;border-radius:12px;border:1px solid #e5e7eb;outline:none;background:#fff}
  textarea{min-height:84px;resize:vertical}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  @media(max-width:650px){.row{grid-template-columns:1fr}}
  .btn{border:none;border-radius:12px;padding:10px 12px;cursor:pointer;font-weight:700}
  .btn.primary{background:#1d4ed8;color:#fff}
  .btn.primary:hover{background:#1b46c8}
  .btn.danger{background:#ef4444;color:#fff}
  .btn.danger:hover{background:#dc2626}
  .btn.gray{background:#e5e7eb;color:#111}
  .btn.gray:hover{background:#d1d5db}
  table{width:100%;border-collapse:collapse;margin-top:10px}
  th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;font-size:13px;vertical-align:top}
  th{background:#f9fafb;color:#111827}
  .tag{padding:4px 10px;border-radius:999px;border:1px solid #e5e7eb;font-size:12px;display:inline-block}
  .tag.admin{border-color:#1d4ed8;color:#1d4ed8}
  .tag.user{border-color:#6b7280;color:#6b7280}
  .thumb{width:64px;height:44px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb}
  .filters a{color:#1d4ed8;text-decoration:none;margin-right:10px;font-size:13px}
  .filters a:hover{text-decoration:underline}
  .section{display:none}
  .section.active{display:block}

  /* Modal */
  .modal{position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;padding:16px;z-index:9999}
  .modal.open{display:flex}
  .modal .panel{width:min(720px, 100%);background:#fff;border-radius:14px;padding:14px;border:1px solid #e5e7eb}
  .modal .head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}
  .modal .head h3{margin:0}
  .close{border:none;background:#e5e7eb;border-radius:10px;padding:8px 10px;cursor:pointer}
  .close:hover{background:#d1d5db}
</style>
</head>
<body>
<div class="layout">

  <aside class="sidebar">
    <div class="brand"><span class="dot"></span> AP Admin</div>
    <div class="nav">
      <button class="active" data-target="users">👤 Users</button>
      <button data-target="menu">🍽️ Menu</button>
    </div>
    <div class="spacer"></div>
    <form action="logout.php" method="post">
      <button class="btn danger" type="submit" style="width:100%;">Logout</button>
    </form>
  </aside>

  <main class="main">
    <?php if ($flash !== ""): ?>
      <div class="msg"><?= h($flash) ?></div>
    <?php endif; ?>

    <div class="content">

      <!-- USERS -->
      <section id="users" class="section active">
        <div class="grid2">

          <div class="card">
            <h2 class="title">Create User</h2>
            <p class="sub">Shto user direkt në DB.</p>

            <form method="post" autocomplete="off">
              <div class="row">
                <div>
                  <label>Username</label>
                  <input name="username" required>
                </div>
                <div>
                  <label>Email</label>
                  <input name="email" type="email" required>
                </div>
              </div>

              <div class="row" style="margin-top:10px;">
                <div>
                  <label>Password (plain)</label>
                  <input name="password" required>
                </div>
                <div>
                  <label>Role</label>
                  <select name="role">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                  </select>
                </div>
              </div>

              <div style="margin-top:12px;">
                <button class="btn primary" type="submit" name="create_user">Create</button>
              </div>
            </form>
          </div>

          <div class="card">
            <h2 class="title">Users List</h2>
            <p class="sub">Edit / Delete user (s’lejohet me fshi vetveten).</p>

            <table>
              <thead>
                <tr>
                  <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($users as $u): ?>
                <tr>
                  <td><?= (int)$u["id"] ?></td>
                  <td><?= h($u["username"]) ?></td>
                  <td><?= h($u["email"]) ?></td>
                  <td><span class="tag <?= $u["role"]==="admin"?"admin":"user" ?>"><?= h($u["role"]) ?></span></td>
                  <td><?= h($u["created_at"]) ?></td>
                  <td style="display:flex;gap:8px;flex-wrap:wrap">
                    <button class="btn gray"
                      onclick='openUserModal(<?= (int)$u["id"] ?>, "<?= h($u["username"]) ?>", "<?= h($u["email"]) ?>", "<?= h($u["role"]) ?>")'>
                      Edit
                    </button>

                    <form method="post" onsubmit="return confirm('Me fshi kete user?');" style="margin:0">
                      <input type="hidden" name="delete_id" value="<?= (int)$u["id"] ?>">
                      <button class="btn danger" type="submit" name="delete_user">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </section>

      <!-- MENU -->
      <section id="menu" class="section">
        <div class="grid2">

          <div class="card">
            <h2 class="title">Add Menu Item</h2>
            <p class="sub">Shto produkt + upload foto.</p>

            <form method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="row">
                <div>
                  <label>Category</label>
                  <select name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                      <option value="<?= (int)$c["id"] ?>"><?= h($c["name"]) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label>Price (6.99)</label>
                  <input name="price" type="number" step="0.01" required>
                </div>
              </div>

              <div class="row" style="margin-top:10px;">
                <div>
                  <label>Title</label>
                  <input name="title" required>
                </div>
                <div>
                  <label>Image (optional)</label>
                  <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                </div>
              </div>

              <div style="margin-top:10px;">
                <label>Description (optional)</label>
                <textarea name="description"></textarea>
              </div>

              <div style="margin-top:12px;">
                <button class="btn primary" type="submit" name="add_item">Add</button>
              </div>
            </form>
          </div>

          <div class="card">
            <h2 class="title">Menu Items</h2>
            <p class="sub">Edit / Delete produkt (ndërron foto opsionale).</p>

            <div class="filters" style="margin:6px 0 10px;">
              <a href="admin_panel.php?cat=all#menu">All</a>
              <a href="admin_panel.php?cat=pije#menu">Pije</a>
              <a href="admin_panel.php?cat=dessert#menu">Dessert</a>
              <a href="admin_panel.php?cat=ushqim#menu">Ushqim</a>
            </div>

            <table>
              <thead>
                <tr>
                  <th>ID</th><th>Category</th><th>Title</th><th>Price</th><th>Image</th><th>Created</th><th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($items as $it): ?>
                <tr>
                  <td><?= (int)$it["id"] ?></td>
                  <td><?= h($it["category"]) ?></td>
                  <td><?= h($it["title"]) ?></td>
                  <td><?= number_format((float)$it["price"], 2) ?>$</td>
                  <td>
                    <?php if (!empty($it["image_path"])): ?>
                      <img class="thumb" src="<?= h($it["image_path"]) ?>" alt="">
                    <?php else: ?>
                      <span class="tag">no image</span>
                    <?php endif; ?>
                  </td>
                  <td><?= h($it["created_at"]) ?></td>
                  <td style="display:flex;gap:8px;flex-wrap:wrap">
                    <button class="btn gray"
                      onclick='openMenuModal(
                        <?= (int)$it["id"] ?>,
                        <?= (int)$it["category_id"] ?>,
                        "<?= h($it["title"]) ?>",
                        "<?= h($it["price"]) ?>",
                        "<?= h($it["description"] ?? "") ?>"
                      )'>
                      Edit
                    </button>

                    <form method="post" onsubmit="return confirm('Me fshi kete produkt?');" style="margin:0">
                      <input type="hidden" name="item_id" value="<?= (int)$it["id"] ?>">
                      <button class="btn danger" type="submit" name="delete_item">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>

          </div>

        </div>
      </section>

    </div>
  </main>
</div>

<!-- USER EDIT MODAL -->
<div class="modal" id="userModal">
  <div class="panel">
    <div class="head">
      <h3>Edit User</h3>
      <button class="close" onclick="closeModal('userModal')">✕</button>
    </div>

    <form method="post" autocomplete="off">
      <input type="hidden" name="edit_user_id" id="edit_user_id">
      <div class="row">
        <div>
          <label>Username</label>
          <input name="edit_username" id="edit_username" required>
        </div>
        <div>
          <label>Email</label>
          <input name="edit_email" id="edit_email" type="email" required>
        </div>
      </div>

      <div class="row" style="margin-top:10px;">
        <div>
          <label>Role</label>
          <select name="edit_role" id="edit_role">
            <option value="user">user</option>
            <option value="admin">admin</option>
          </select>
        </div>
        <div>
          <label>New Password (leave empty to keep old)</label>
          <input name="edit_password" id="edit_password" placeholder="(optional)">
        </div>
      </div>

      <div style="margin-top:12px;display:flex;gap:10px;">
        <button class="btn primary" type="submit" name="edit_user">Save</button>
        <button class="btn gray" type="button" onclick="closeModal('userModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- MENU EDIT MODAL -->
<div class="modal" id="menuModal">
  <div class="panel">
    <div class="head">
      <h3>Edit Menu Item</h3>
      <button class="close" onclick="closeModal('menuModal')">✕</button>
    </div>

    <form method="post" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" name="edit_item_id" id="edit_item_id">

      <div class="row">
        <div>
          <label>Category</label>
          <select name="edit_category_id" id="edit_category_id" required>
            <?php foreach ($categories as $c): ?>
              <option value="<?= (int)$c["id"] ?>"><?= h($c["name"]) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Price</label>
          <input name="edit_price" id="edit_price" type="number" step="0.01" required>
        </div>
      </div>

      <div class="row" style="margin-top:10px;">
        <div>
          <label>Title</label>
          <input name="edit_title" id="edit_title" required>
        </div>
        <div>
          <label>New Image (optional)</label>
          <input type="file" name="edit_image" accept=".jpg,.jpeg,.png,.webp">
        </div>
      </div>

      <div style="margin-top:10px;">
        <label>Description</label>
        <textarea name="edit_description" id="edit_description"></textarea>
      </div>

      <div style="margin-top:12px;display:flex;gap:10px;">
        <button class="btn primary" type="submit" name="edit_item">Save</button>
        <button class="btn gray" type="button" onclick="closeModal('menuModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  const navButtons = document.querySelectorAll('.nav button');
  const sections = document.querySelectorAll('.section');

  function activate(id){
    sections.forEach(s => s.classList.toggle('active', s.id === id));
    navButtons.forEach(b => b.classList.toggle('active', b.dataset.target === id));
    location.hash = id;
  }
  navButtons.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.target)));

  const hash = (location.hash || '#users').replace('#','');
  if (hash === 'menu' || hash === 'users') activate(hash);

  window.addEventListener('load', () => {
    const h = (location.hash || '#users').replace('#','');
    if (h === 'menu') activate('menu');
  });

  function openUserModal(id, username, email, role){
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = "";
    document.getElementById('userModal').classList.add('open');
  }

  function openMenuModal(id, categoryId, title, price, desc){
    document.getElementById('edit_item_id').value = id;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_description').value = desc || "";
    document.getElementById('menuModal').classList.add('open');
  }

  function closeModal(id){
    document.getElementById(id).classList.remove('open');
  }

  // close modal on click outside
  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', (e) => {
      if (e.target === m) m.classList.remove('open');
    });
  });
</script>
</body>
</html>
