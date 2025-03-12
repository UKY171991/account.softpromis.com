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
  
  <!-- Sidebar -->
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="dashboard.php">
        <img src="assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="income-category.php">
            <i class="material-symbols-rounded opacity-5">category</i>
            <span class="nav-link-text ms-1">Income Categories</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                  $query = "SELECT * FROM income_categories";
                  $result = mysqli_query($conn, $query);
                  $count = 1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td class='text-xs'>{$count}</td>
                            <td class='text-xs'>{$row['category_name']}</td>
                            <td class='text-center'>
                              <a href='edit-category.php?id={$row['id']}' class='text-info mx-2'><i class='fa fa-edit'></i></a>
                              <a href='delete-category.php?id={$row['id']}' class='text-danger mx-2'><i class='fa fa-trash'></i></a>
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