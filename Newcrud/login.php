<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
              // Include the database connection file
              include("db_conn.php");

              // Check if form is submitted
              if(isset($_POST['submit'])){
                  echo "<p>Form submitted</p>"; // Debugging output

                  // Get and sanitize input values
                  $id = mysqli_real_escape_string($conn, $_POST['id']);
                  $email = mysqli_real_escape_string($conn, $_POST['email']);

                  // Check if ID and email are populated
                  if(empty($id) || empty($email)) {
                      echo "<div class='message'><p>ID or Email is missing!</p></div><br>";
                  } else {
                      // Query to fetch user data with matching ID and email
                      $query = "SELECT * FROM crud WHERE Id='$id' AND Email='$email'";
                      $result = mysqli_query($conn, $query);

                      // Check if query succeeded
                      if (!$result) {
                          die("Query Error: " . mysqli_error($conn)); // Display query error
                      }

                      $row = mysqli_fetch_assoc($result);

                      if(is_array($row) && !empty($row)){
                          // Store session variables on successful login
                          $_SESSION['valid'] = $row['id'];
                          $_SESSION['email'] = $row['email'];
                          // Redirect to welcome page after login
                          header("Location: welcome.php");
                          exit();
                      } else {
                          // Display error message on incorrect ID or Email
                          echo "<div class='message'><p>Incorrect ID or Email</p></div><br>";
                      }
                  }
              }
            ?>
            
            <!-- Login Form -->
            <header>Login</header>
            <form action="login.php" method="post">  <!-- Updated form action to login.php -->
                <div class="field input">
                    <label for="id">ID</label>
                    <input type="text" name="id" id="id" autocomplete="on" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="on" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                
                <div class="links">
                    Don't have an account? <a href="add-new.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
