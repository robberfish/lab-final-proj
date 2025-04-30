<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('db.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

//Get all trades with user names
$query = "
    SELECT 
        t.id,
        t.status,
        t.completed_at,
        u1.username AS owner_name,
        u2.username AS buddy_name
    FROM trades t
    LEFT JOIN users u1 ON t.owner_id = u1.id
    LEFT JOIN users u2 ON t.buddy_id = u2.id
    ORDER BY t.completed_at DESC, t.id DESC
";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trade Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/fresh-bootstrap-table.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
    <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 72px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
        <h1 id='p1' style="color: rgb(255, 255, 255); text-align: left; font-weight: bolder; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:32px;">
            BEE <br> BUY <br>
        </h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="gallery.php">SHOP</a></li>
                <li><a href="bio.html">BIO</a></li>
                <li><a href="contactus.html">CONTACT</a></li>
                <li><a href="cart.php" class="active">CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
                <li><a href="submit.php">PENDING</a></li>
                <a href="https://facebook.com" target="_blank"> 
                    <i class="fa-brands fa-facebook-f" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://x.com" target="_blank"> 
                    <i class="fa-brands fa-x-twitter" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://instagram.com" target="_blank"> 
                    <i class="fa-brands fa-instagram" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a>
                </ul>
        </nav>
    </header>
    <meta charset="utf-8">
    <style>
       table {
            width: 80%;
            margin: 16px auto;
            border-collapse: collapse;
        }
        body{
            background-color: white;
            background-image:none;
        }
        h1{
            color:black;
            font-size: 24px;
        }
        form{
            width: 80%;
            margin: 16px auto;
            border-collapse: collapse;
        }
        th {
            background-color:rgba(242, 242, 242, 0.72);
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        tr:hover {
            background-color:rgba(241, 241, 241, 0.76);
        }
        a{
            color: black;
            font-weight: bolder;
        }
        a:hover{
            font-weight: bolder;
        }
        .group-buttons {
            text-align: center; 
            margin-top: 20px;   
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">All Trades (Admin View)</h2>
    <div class="form">
    <div class="fresh-table full-color-orange">
    <table id="fresh-table" class="table">
        <tr>
            <th>ID</th>
            <th>Owner</th>
            <th>Buddy</th>
            <th>Status</th>
            <th>Completed At</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['owner_name']) ?></td>
                <td><?= htmlspecialchars($row['buddy_name']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                <td><?= $row['completed_at'] ? htmlspecialchars($row['completed_at']) : '-' ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div class="group-buttons">
            <button><p><a href="adduser.php">Add New User</a></p></button>
            <button><p><a href="itemdash.php">Item Dashboard</a></p></button>
            <button><p><a href="logout.php">Logout</a></p></button>
        </div>
        </div>
        </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script type="text/javascript">
  var $table = $('#fresh-table')
  $(function () {
    $table.bootstrapTable({
      classes: 'table table-hover table-striped',
      search: true,
      striped: true,

      formatShowingRows: function (pageFrom, pageTo, totalRows) {
        return ''
      },
      formatRecordsPerPage: function (pageNumber) {
        return pageNumber + ' rows visible'
      }
    })
  })
  </script>

