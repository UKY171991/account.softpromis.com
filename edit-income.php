<?php
include 'inc/auth.php';
include 'inc/config.php';

// Get Income ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid income entry.";
    header("Location: income-list.php");
    exit();
}

$income_id = intval($_GET['id']);
$query = "SELECT * FROM income WHERE id = $income_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error_msg'] = "Income entry not found.";
    header("Location: income-list.php");
    exit();
}

$income = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Income</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark">Edit Income</h4>
                </div>
            </div>
            <div class="row mt-4 d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white d-flex align-items-center">
                            <h6 class="mb-0 text-white">Edit Income</h6>
                        </div>
                        <div class="card-body">
                            <form action="update-income.php" method="POST">
                                <input type="hidden" name="id" value="<?= $income['id']; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control border" name="name" value="<?= $income['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control border" name="phone" value="<?= $income['phone']; ?>" pattern="[0-9]{10}" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control border" name="category_id" required>
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                $category_query = "SELECT * FROM income_categories ORDER BY category_name ASC";
                                                $category_result = mysqli_query($conn, $category_query);
                                                while ($row = mysqli_fetch_assoc($category_result)) {
                                                    $selected = ($row['id'] == $income['category_id']) ? 'selected' : '';
                                                    echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Actual Amount</label>
                                            <input type="number" class="form-control border no-spinner" name="actual_amount" value="<?= $income['actual_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Received Amount</label>
                                            <input type="number" class="form-control border no-spinner" name="received_amount" value="<?= $income['received_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Balance Amount</label>
                                            <input type="text" class="form-control border" name="balance_amount" value="<?= $income['actual_amount'] - $income['received_amount']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Entry</label>
                                            <input type="text" class="form-control border" name="date_of_entry" value="<?= $income['date_of_entry']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control border" name="description" required><?= $income['description']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Update Income</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
