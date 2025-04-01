<?php
include('db.php'); 
// Handle delete user action
if(isset($_GET['delete_id'])){
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM users WHERE id = $delete_id";
    if(mysqli_query($conn, $delete_query)){
        echo "User deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM users"; //get all from my table of users
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        table {
            width: 100%;
        }
        table, th, td {
            border: 1px black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .delete-btn {
            color: blue;
        }
    </style>
</head>
<body>

    <h1>Users</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created-At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['create_datetime'] . "</td>";
                    echo "<td><a href='?delete_id=" . $row['id'] . "' class='delete-btn'>Delete</a></td>";
                    echo "</tr>";
                }//display my users
            } else {
                echo "<tr><td colspan='5'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
<?php
mysqli_close($conn);
?>
