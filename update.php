<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('http://localhost/lab_5b/images/backimages.jpg');
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .cancel-link {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }

        .cancel-link a {
            text-decoration: none;
            color: #007BFF;
        }

        .cancel-link a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .message p {
            font-weight: bold;
            font-size: 16px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Update User</h1>

        <?php
        
        $host = "localhost"; 
        $dbname = "lab_5b"; 
        $username = "root"; 
        $password = ""; 

        
        $conn = new mysqli($host, $username, $password, $dbname);

        
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $matrics = $_POST['matrics'];
            $name = $_POST['name'];
            $accessLevel = $_POST['role'];

            $stmt = $conn->prepare("SELECT name FROM users WHERE matrics = ?");
            $stmt->bind_param("s", $matrics);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {

                $updateStmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matrics = ?");
                $updateStmt->bind_param("sss", $name, $accessLevel, $matrics);

                if ($updateStmt->execute()) {
                    echo "<div class='message success'><p>Data updated successfully for Matric: $matrics.</p></div>";
                } else {
                    echo "<div class='message error'><p>Error updating data: " . $conn->error . "</p></div>";
                }

                $updateStmt->close();
            } else {
                echo "<div class='message error'><p>Matric not found. Please check the Matric ID and try again.</p></div>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <form method="POST" action="">
            <label for="matrics">Matric:</label>
            <input type="text" id="matrics" name="matrics" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="role">Access Level:</label>
            <input type="text" id="role" name="role" required>

            <input type="submit" value="Submit">
        </form>

        <div class="cancel-link">
            <a href="display.php">Cancel</a>
        </div>
    </div>

</body>
</html>
