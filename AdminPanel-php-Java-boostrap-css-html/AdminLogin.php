<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: url('images/pexels-joyston-judah-331625-933054.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrap {
            background-color: rgba(255, 255, 255, 0.4); /* Increased transparency */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 320px;
            padding: 20px;
            text-align: center;
        }

        .heading {
            text-align: center;
            color: red;
        }

        .form-group {
            margin-bottom: 20px;
        }

        select, input[type="email"], input[type="text"], input[type="password"], input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 3px;
            border: 1px solid #dddfe2;
            border-radius: 25px; /* Rounded border */
            background-color: rgba(240, 242, 245, 0.3); /* Increased transparency */
            font-size: 14px;
            color: #1c1e21;
            outline: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        button:hover {
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: greywhite;
            font-size: 14px;
            outline: none;
            color: gray;
            cursor: pointer;
            /* Changed border color to red */
            border-radius: 25px; /* Rounded border */
            transition: var(--transition-timing);
        }

        input[type="submit"], input[type="button"] {
            background-color: greywhite;
            color: gray;
            text-transform: uppercase;
            cursor: pointer;
            font-weight: bold;
            border-radius: 25px; /* Rounded border */
            transition: var(--transition-timing);
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #FF91A4;
        }

        p {
            text-align: center;
        }

        .error-message {
            color: white;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .password-strength {
            font-size: 12px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <h1 class="heading">Login</h1>
    <form id="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <input type="text" name="Email" id="Email" required placeholder="Email">
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" required placeholder="Password" onkeyup="checkPasswordStrength()">
        </div>
        <div class="form-group">
            <select name="roll" id="roll" required>
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div id="password-strength" class="password-strength"></div>
        <input type="submit" name="submit" value="Log In">
        <p>or</p>
        <button type="button" onclick="window.location.href='/Adminsignup.php'">Register</button>
    </form>

    <?php
    session_start(); // Start PHP session

    // Prevent caching
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root"; // Change this to your database username
        $password = ""; // Change this to your database password
        $dbname = "attendanceSystem";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Sanitize input data to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_POST['Email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $roll = mysqli_real_escape_string($conn, $_POST['roll']);

        // Determine which table to query based on roll selection
        if ($roll === "Admin") {
            $table = "admin";
        } elseif ($roll === "user") {
            $table = "user";
        } else {
            die("Invalid role selected");
        }

        // Query to check credentials
        $sql = "SELECT Email, password FROM $table WHERE Email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // After verifying credentials
            $_SESSION['Email'] = $email;
            $_SESSION['loggedin'] = true; // Set login status
            
            // Redirect based on role
            if ($roll === "Admin") {
                header("Location: /AdminPanel.php");
            } elseif ($roll === "user") {
                header("Location: /UserPanel.php");
            }
            exit(); // Ensure immediate redirection
        } else {
            $error_message = 'Invalid email or password';
        }

        $conn->close();
    }

    // Display error message if login fails
    if (isset($error_message)) {
        echo '<div class="error-message">' . $error_message . '</div>';
    }
    ?>
</div>
<script>
    function checkPasswordStrength() {
        var password = document.getElementById("password").value;
        var strengthBadge = document.getElementById("password-strength");

        var strength = 0;
        if (password.match(/[a-z]+/)) {
            strength += 1;
        }
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        }
        if (password.match(/[0-9]+/)) {
            strength += 1;
        }
        if (password.match(/[!@#$%^&*()]+/)) {
            strength += 1;
        }

        if (password.length >= 8 && password.length <= 16) {
            if (password.length <= 9) {
                strengthBadge.textContent = "Weak";
            } else if (password.length <= 12) {
                strengthBadge.textContent = "Medium";
            } else {
                strengthBadge.textContent = "Strong";
            }
        } else {
            strengthBadge.textContent = "";
        }
    }
</script>
</body>
</html>
