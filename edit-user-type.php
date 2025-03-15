<?php
include 'inc/auth.php';
include 'inc/config.php';

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: list-user-type.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch the existing user type
$query = "SELECT * FROM use_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$userType = $result->fetch_assoc();

if (!$userType) {
    $_SESSION['error_msg'] = "User type not found.";
    header("Location: list-user-type.php");
    exit();
}

// Update the user type
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = trim($_POST['type']);

    if (!empty($type)) {
        $updateQuery = "UPDATE use_type SET type = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $type, $id);

        if ($updateStmt->execute()) {
            $_SESSION['success_msg'] = "User type updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error: " . $conn->error;
        }

        $updateStmt->close();
        header("Location: list-user-type.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "User type cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User Type</title>
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
                            <h6 class="mb-0">Edit User Type</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <input type="text" class="form-control border" name="type" value="<?= htmlspecialchars($userType['type']); ?>" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Update</button>
                                    <a href="list-user-type.php" class="btn btn-secondary">Cancel</a>
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
