<?php
include 'inc/auth.php';
include 'inc/config.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid request!";
    header("Location: expenditure.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM expenditure WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$expenditure = $result->fetch_assoc();

if (!$expenditure) {
    $_SESSION['error_msg'] = "Expenditure not found!";
    header("Location: expenditure.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Expenditure</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark">Edit Expenditure</h4>
                </div>
            </div>
            <div class="row mt-4 d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0 text-white">Update Expenditure Details</h6>
                        </div>
                        <div class="card-body">
                            <form action="update-expenditure.php" method="POST">
                                <input type="hidden" name="id" value="<?= $expenditure['id']; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control border" name="name" value="<?= $expenditure['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control border" name="phone" value="<?= $expenditure['phone']; ?>" pattern="[0-9]{10}" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control border" name="category_id" required>
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                $query = "SELECT * FROM expenditure_categories ORDER BY category_name ASC";
                                                $result = $conn->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($row['id'] == $expenditure['category_id']) ? 'selected' : '';
                                                    echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Total Amount</label>
                                            <input type="number" class="form-control border" name="total_amount" value="<?= $expenditure['total_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Paid Amount</label>
                                            <input type="number" class="form-control border" name="paid_amount" value="<?= $expenditure['paid_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Entry</label>
                                            <input type="text" class="form-control border" name="date_of_entry" id="datepicker" value="<?= date('d-m-Y', strtotime($expenditure['date_of_entry'])); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" });
        });
    </script>
</body>
</html>
