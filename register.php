<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
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
            max-width: 400px;
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

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus, select:focus {
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

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
        }

        .form-footer a:hover {
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
        <h1>Register New User</h1>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab_5b";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $matrics = $_POST['matrics'];
            $name = $_POST['name'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = $_POST['role'];

            $stmt = $conn->prepare("INSERT INTO users (matrics, name, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $matrics, $name, $password, $role);

            if ($stmt->execute()) {
                echo "<div class='message success'><p>New record created successfully.</p></div>";
            } else {
                echo "<div class='message error'><p>Error: " . $stmt->error . "</p></div>";
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

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="" style="display:none;" selected disabled>Please select</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select>

            <input type="submit" value="Register">
        </form>

        <div class="form-footer">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>

</body>
</html>
