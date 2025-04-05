<?php
session_start();
include 'inc/config.php';


// $username = "admin";
// $password = password_hash("admin@123", PASSWORD_DEFAULT);
// $role = "admin";

// $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
// $stmt->bind_param("sss", $username, $password, $role);

// if ($stmt->execute()) {
//     echo "User created successfully.";
// } else {
//     echo "Error: " . $conn->error;
// }





if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
            header("location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background: #f5f5f5;
    }
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
<div class="login-container">
  <h3 class="mb-4">Account Login</h3>
  <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="post">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>
</body>
</html>
