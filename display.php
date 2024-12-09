<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['matrics'])) {
    header("Location: login.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['delete'])) {
    $matricsToDelete = $_GET['delete'];

    
    $stmt = $conn->prepare("DELETE FROM users WHERE matrics = ?");
    $stmt->bind_param("s", $matricsToDelete);
    if ($stmt->execute()) {
        echo "<p style='color:green;'>User with Matric $matricsToDelete has been deleted successfully.</p>";
    } else {
        echo "<p style='color:red;'>Error deleting user: " . $stmt->error . "</p>";
    }
    $stmt->close();
}


$sql = "SELECT matrics, name, role FROM users";
$result = $conn->query($sql);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('http://localhost/lab_5b/images/backimages1.jpg');
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-top: 20px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .logout-btn {
            margin-bottom: 20px;
            text-align: right;
        }

        .logout-btn button {
            padding: 10px 20px;
            background-color: #FF4C4C;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout-btn button:hover {
            background-color: #e63946;
        }

        .action-links a {
            text-decoration: none;
            color: #007BFF;
            margin: 0 10px;
        }

        .action-links a:hover {
            color: #0056b3;
        }

        .empty-row td {
            text-align: center;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Users Table</h1>
        
        
        <form method="POST" class="logout-btn">
            <button type="submit" name="logout">Logout</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['matrics']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td class='action-links'>
                                <a href='update.php?matrics=" . urlencode($row['matrics']) . "'>Update</a> | 
                                <a href='?delete=" . urlencode($row['matrics']) . "' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr class='empty-row'><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    
    $conn->close();
    ?>
</body>
</html>
