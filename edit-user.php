<?php
include 'inc/auth.php';
include 'inc/config.php';

$id = intval($_GET['id']);

// Check if ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: list-users.php");
    exit();
}

// Fetch existing user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
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
                            <h6 class="mb-0">Edit User</h6>
                        </div>
                        <div class="card-body">
                            <form action="process-edit-user.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select class="form-control border" name="use_type" required>
                                        <option value="">-- Select User Type --</option>
                                        <?php
                                        $result = $conn->query("SELECT * FROM use_type ORDER BY id ASC");
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = ($row['id'] == $user['use_type']) ? 'selected' : '';
                                            echo "<option value='{$row['id']}' $selected>{$row['type']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control border" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password (Leave empty to keep current password)</label>
                                    <input type="password" class="form-control border" name="password">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Update</button>
                                    <a href="list-users.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>
