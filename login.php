<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
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
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #0056b3;
        }

        .message {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-top: 15px;
        }

        .success {
            color: green;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Login</h1>

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
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT password FROM users WHERE matrics = ?");
            $stmt->bind_param("s", $matrics);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();

                if (password_verify($password, $hashedPassword)) {
                    // Start a session to maintain user login
                    session_start();
                    $_SESSION['matrics'] = $matrics;

                    // Redirect to display.php
                    header("Location: display.php");
                    exit(); // Always call exit() after a header redirect
                } else {
                    echo "<div class='message'>Invalid username or password, try logging in again.</div>";
                }
            } else {
                echo "<div class='message'>Invalid username or password, try logging in again.</div>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <form method="POST" action="">
            <label for="matrics">Matric:</label>
            <input type="text" id="matrics" name="matrics" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <div class="form-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>
</html>
