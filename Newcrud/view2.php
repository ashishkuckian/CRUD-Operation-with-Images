<?php
include "db_conn.php";

if (isset($_GET["id"])) {
  $user_id = intval($_GET["id"]); // Use intval to sanitize the user ID

  // Prepare SQL query to fetch user data
  $sql = "SELECT * FROM `table2` WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  // Bind the user ID parameter
  mysqli_stmt_bind_param($stmt, "i", $user_id);

  // Execute the statement
  mysqli_stmt_execute($stmt);
  
  // Get the result
  $result = mysqli_stmt_get_result($stmt);

  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
  } else {
    echo "User not found.";
    exit;
  }
} else {
  echo "No user ID provided.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>View User</title>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card p-4 shadow-lg" style="width: 24rem;">
    <div class="text-center mb-4">
      <h2>User Details</h2>
    </div>

    <div class="text-center mb-3">
      <!-- Check if profile image exists, otherwise show a default one -->
      <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'man.jpeg'); ?>" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
    </div>

    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>ID:</strong> <?php echo htmlspecialchars($user["id"]); ?></li>
      <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($user["first_name"] ?? 'N/A'); ?></li>
      <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($user["last_name"] ?? 'N/A'); ?></li>
      <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user["email"] ?? 'N/A'); ?></li>
      <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($user["gender"] ?? 'N/A'); ?></li>
    </ul>

    <div class="text-center mt-4">
      <a href="account.php" class="btn btn-primary">Back to List</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
