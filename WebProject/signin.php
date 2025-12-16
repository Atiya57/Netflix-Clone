<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign In</title>
    <style>
        body {
            background-color: #000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #form-container {
            background-color: rgb(174, 20, 20);
            text-align: center;
            width: 350px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #d11010;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #e50914;
            color: white;
            width: 100%;
            height: 40px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #b00710;
        }
        #extra-links {
            font-size: 12px;
            margin-top: 10px;
        }
        #extra-links a {
            color: #fff;
            text-decoration: none;
        }
        #extra-links a:hover {
            text-decoration: underline;
        }
        #error-message {
            color: yellow;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="signin.php" method="POST">
        <div id="form-container">
            <h1>Sign In</h1>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Sign In</button> 
            <div id="extra-links">New to Netflix? <a href="index.html">Sign up now</a></div>
            <div id="error-message">
                <?php if (isset($_GET['error'])) { echo htmlspecialchars($_GET['error']); } ?>
            </div>
        </div>
    </form>
</body>
</html>
<?php
// Database credentials
$server = "localhost";
$username = "root";
$password = "";
$dbname = "netflix_users";

// Database connection
$conn = mysqli_connect($server, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM `users_data` WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Login successful - Redirect to main.html
            header("Location: main.html");
            exit();
        } else {
            // Login failed - Redirect back with an error message
            $error = urlencode("Invalid email or password.");
            header("Location: signin.php?error=$error");
            exit();
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Prepared Statement Failed: " . mysqli_error($conn));
    }
}

// Close the database connection
mysqli_close($conn);
?>
