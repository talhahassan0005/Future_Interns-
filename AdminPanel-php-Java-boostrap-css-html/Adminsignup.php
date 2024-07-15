<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/xampp/htdocs/Admin_Panel.css">
    <script src="/htdocs/Admin_Panel.js"></script>
    <title>RegistrationForm</title>
    
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
            padding: 4px;
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
        <h1 class="heading">Sign Up</h1>
        <form method="post" id="registrationForm">
            <div class="form-group">
                <input type="text" name="Name" id="Name" required placeholder="Enter your name">
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="Enter the Password">
            </div>
            <div class="form-group">
                <select name="role" id="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <input type="submit" name="submit" value="Register">
            <p>or</p>
            <button type="button" onclick="window.location.href='/AdminLogin.php'">Already have an Account</button>
        </form>
    </div>


    <?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (!empty($_POST['Name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        // Sanitize input data to prevent SQL injection
        $username = htmlspecialchars($_POST['Name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);

        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Connect to your database (replace database credentials with your own)
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "attendanceSystem";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Use prepared statements to prevent SQL injection
            if ($role === "admin") {
                $sql = $conn->prepare("INSERT INTO admin (Name, email, password) VALUES (?, ?, ?)");
            } else {
                $sql = $conn->prepare("INSERT INTO user (Name, email, password) VALUES (?, ?, ?)");
            }

            $sql->bind_param("sss", $username, $email, $password);

            if ($sql->execute()) {
                echo "<script>alert('Successfully Registered!');</script>";
                echo "<script>clearForm();</script>";
            } else {
                echo "Error: " . $sql->error;
            }

            $sql->close();
            $conn->close();
        } else {
            echo "<script>alert('Invalid email format');</script>";
        }
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}
?>

    <script>
        function clearForm() {
            document.getElementById("registrationForm").reset();
        }
    </script>
</body>
</html>
