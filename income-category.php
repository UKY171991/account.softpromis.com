<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Add Income Category</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <?php include 'inc/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

  	<?php include 'inc/topbar.php'; ?>

    <div class="container-fluid py-4">
      
      <!-- Page Title -->
      <div class="row">
        <div class="col-12">
          <h4 class="text-dark">Add Income Category</h4>
        </div>
      </div>

      <!-- Form to Add Income Category -->
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-gradient-dark text-white">
              <h6 class="mb-0">Add New Category</h6>
            </div>
            <div class="card-body">

            	<?php
					session_start();
					if (isset($_SESSION['success_msg'])) {
					    echo "<div class='alert alert-success'>{$_SESSION['success_msg']}</div>";
					    unset($_SESSION['success_msg']);
					}
					if (isset($_SESSION['error_msg'])) {
					    echo "<div class='alert alert-danger'>{$_SESSION['error_msg']}</div>";
					    unset($_SESSION['error_msg']);
					}
					?>

              <form action="process-income-category.php" method="POST">
                <div class="mb-3">
                  <label class="form-label">Category Name</label>
                  <input type="text" class="form-control" name="category_name" required>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn bg-gradient-dark">Add Category</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Table to Display Categories -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-gradient-dark text-white">
              <h6 class="mb-0">Income Categories</h6>
            </div>
            <div class="card-body px-3">
              <table class="table align-items-center mb-0">
				    <thead>
				        <tr>
				            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">#</th>
				            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Category Name</th>
				            <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Action</th>
				        </tr>
				    </thead>
				    <tbody>
				        <?php
				        include 'inc/config.php';
				        $query = "SELECT * FROM income_categories ORDER BY id DESC";
				        $result = mysqli_query($conn, $query);
				        $count = 1;
				        while ($row = mysqli_fetch_assoc($result)) {
				            echo "<tr>
				                    <td class='text-xs'>{$count}</td>
				                    <td class='text-xs'>{$row['category_name']}</td>
				                    <td class='text-center'>
				                        <a href='edit-category.php?id={$row['id']}' class='text-info mx-2'><i class='fa fa-edit'></i></a>
				                        <a href='delete-category.php?id={$row['id']}' class='text-danger mx-2' onclick='return confirm(\"Are you sure you want to delete this category?\")'><i class='fa fa-trash'></i></a>
				                    </td>
				                  </tr>";
				            $count++;
				        }
				        ?>
				    </tbody>
				</table>

            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- Core JS Files -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>