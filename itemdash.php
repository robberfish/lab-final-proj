<?php
require('db.php'); //connect to db
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM `items` WHERE id = '$delete_id'";

    if (mysqli_query($con, $delete_query)) {
        header("Location: itemdash.php"); // Redirect to refresh the page
        exit();
    } else {
        echo "Error". mysqli_error($con);
    }
}

//get all users' info from the database
$query = "SELECT id, name, image_url, price FROM `items`";
$result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
     <!--for some reason application would not allow styles.css so I had to do styling here-->
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
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-weight:bold;
            background-color:rgba(241, 241, 241, 0.82);
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
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
            background-color:rgba(241, 241, 241, 0.21);
            font-weight: bolder;
            text-decoration: underline;
            color:rgb(11, 125, 32);
        }
        button{
            border: none;
            padding: 10px 12px;
            margin: 0 10px;     
            font-weight:bolder;      
        }
        .group-buttons {
            text-align: center; 
            margin-top: 20px;   
        }


    </style>
</head>
<body>
    <div class="form">
        <h1>ITEM DASHBOARD</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name </th>
                    <th>URL</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr><!--info from the db shown here-->
                        <td><?php echo htmlspecialchars($row['id']);?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['image_url']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']);?></td>
                        <td>
                            <a href="itemdash.php?delete_id=<?php echo $row['id']; ?>">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="group-buttons">
            <button><p><a href="additem.php">Add New item</a></p></button>
            <button><p><a href="dashboard.php">User Dashboard</a></p></button>
            <button><p><a href="logout.php">Logout</a></p></button>
        </div>

    </div>
</body>
</html>
