<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $use_type = intval($_POST['use_type']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (!empty($use_type) && !empty($username) && !empty($password)) {
        // Hash the password before storing it
        $hashed_password = md5($password); // You can replace md5 with password_hash() for better security

        // Insert into the database
        $query = "INSERT INTO users (use_type, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $use_type, $username, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "User added successfully.";
        } else {
            $_SESSION['error_msg'] = "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_msg'] = "All fields are required.";
    }

    header("Location: add-user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <?php if (isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_msg']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_msg']); ?>
                    <?php endif; ?>

                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Add New User</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select class="form-control border" name="use_type" required>
                                        <option value="">-- Select User Type --</option>
                                        <?php
                                        $result = $conn->query("SELECT * FROM use_type ORDER BY id ASC");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['id']}'>{$row['type']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control border" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control border" name="password" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="list-users.php" class="btn btn-secondary">View Users</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>
