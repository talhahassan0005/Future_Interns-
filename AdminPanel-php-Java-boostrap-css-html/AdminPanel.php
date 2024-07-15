
<?php
// Check if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start PHP session
}

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /AdminLogin.php");
    exit;
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: /AdminLogin.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .page-header {
            background-color: darkgrey;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .table-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h2>User Management</h2>
    </div>

    <!-- Display success messages -->
    <?php
// Start session at the beginning of the script

    if (isset($_SESSION['addSuccess']) && $_SESSION['addSuccess']) {
        echo '<div id="addSuccessMessage" class="alert alert-success" role="alert">User added successfully!</div>';
        unset($_SESSION['addSuccess']); // Unset session variable after displaying
    }
    if (isset($_SESSION['deleteSuccess']) && $_SESSION['deleteSuccess']) {
        echo '<div id="deleteSuccessMessage" class="alert alert-success" role="alert">User deleted successfully!</div>';
        unset($_SESSION['deleteSuccess']); // Unset session variable after displaying
    }
    if (isset($_SESSION['editSuccess']) && $_SESSION['editSuccess']) {
        echo '<div id="editSuccessMessage" class="alert alert-success" role="alert">User updated successfully!</div>';
        unset($_SESSION['editSuccess']); // Unset session variable after displaying
    }
    ?>

    <!-- Button group for action buttons -->
    <div class="action-buttons">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            Add User
        </button>
        <a href="AdminLogout.php" class="btn btn-danger ml-2">Logout</a> <!-- Logout button -->
    </div>

    <!-- Modal for adding user -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addUserForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="addUser">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table to display users -->
    <table class="table table-bordered">
        <thead class="table-header">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // Replace with your MySQL username
        $password = ""; // Replace with your MySQL password
        $dbname = "attendancesystem"; // Replace with your database name

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Add new user
        if (isset($_POST['addUser'])) {
            $username = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Insert user into database
            $sql = "INSERT INTO user (name, email, password) VALUES ('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['addSuccess'] = true;
                echo '<script>window.location.href = "/AdminPanel.php";</script>';
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
            }
        }

        // Delete user
        if (isset($_GET['delete'])) {
            $userId = $_GET['delete'];

            // Delete user from database
            $sql = "DELETE FROM user WHERE id = $userId";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['deleteSuccess'] = true;
                echo '<script>window.location.href = "/AdminPanel.php";</script>';
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
            }
        }

        // Edit user
        if (isset($_POST['editUser'])) {
            $userId = $_POST['editUserId'];
            $username = $_POST['editName'];
            $email = $_POST['editEmail'];

            // Update user in database
            $sql = "UPDATE user SET name='$username', email='$email' WHERE id=$userId";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['editSuccess'] = true;
                echo '<script>window.location.href = "/AdminPanel.php";</script>';
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Error updating record: ' . $conn->error . '</div>';
            }
        }

        // Fetch users from database
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>';
                echo '<button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#editUserModal' . $row['id'] . '">Edit</button>';
                echo '<a href="?delete=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                echo '</td>';
                echo '</tr>';

                // Edit user modal
                echo '<div class="modal fade" id="editUserModal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel' . $row['id'] . '" aria-hidden="true">';
                echo '<div class="modal-dialog" role="document">';
                echo '<div class="modal-content">';
                echo '<form method="POST">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="editUserModalLabel' . $row['id'] . '">Edit User</h5>';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<div class="form-group">';
                echo '<label for="editName">Username</label>';
                echo '<input type="text" class="form-control" id="editName" name="editName" value="' . $row['name'] . '" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="editEmail">Email</label>';
                echo '<input type="email" class="form-control" id="editEmail" name="editEmail" value="' . $row['email'] . '" required>';
                echo '</div>';
                echo '</div>';
                echo '<div class="modal-footer">';
                echo '<input type="hidden" name="editUserId" value="' . $row['id'] . '">';
                echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                echo '<button type="submit" class="btn btn-primary" name="editUser">Save changes</button>';
                echo '</div>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<tr><td colspan="4">No users found</td></tr>';
        }

        $conn->close();
        ?>
        </tbody>
    </table>

    <!-- JavaScript for auto dismissal of success messages -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#addSuccessMessage').fadeOut('slow');
                $('#deleteSuccessMessage').fadeOut('slow');
                $('#editSuccessMessage').fadeOut('slow');
            }, 5000); // 5000 milliseconds = 5 seconds
        });
    </script>
</div>
</body>
</html>
