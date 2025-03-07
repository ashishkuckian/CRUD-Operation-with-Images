<?php
include "db_conn.php";
$id = $_GET["id"]; // Fetching ID from URL

if (isset($_POST["submit"])) {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone']; // New phone field
    $address = $_POST['address']; // New address field
    $newsletter_subscription = isset($_POST['newsletter_subscription']) ? 1 : 0; // Checkbox for newsletter subscription

    // File Upload Logic
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_image);
    $uploadOk = 1; // To check if file can be uploaded
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!empty($profile_image)) {
        $allowed_file_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowed_file_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($_FILES['profile_image']['size'] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            // Delete old image if exists and replace with new one
            if (!empty($_POST['existing_image']) && file_exists($_POST['existing_image'])) {
                unlink($_POST['existing_image']); // Remove the old image
            }
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
        } else {
            // Keep the existing image if file checks fail
            $target_file = $_POST['existing_image'];
        }
    } else {
        // If no new image is uploaded, retain the existing one
        $target_file = $_POST['existing_image'];
    }

    // Update query with the new fields (phone, address, newsletter_subscription, and image field)
    $sql = "UPDATE `crud` SET 
            `firstname`='$first_name', 
            `lastname`='$last_name', 
            `email`='$email', 
            `gender`='$gender',
            `phone`='$phone', 
            `address`='$address',
            `newsletter_subscription`='$newsletter_subscription',
            `profile_image`='$target_file' 
            WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php?msg=Data updated successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}

// Fetching existing data to display in the form
$sql = "SELECT * FROM `crud` WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <title>Edit User</title>
</head>

<body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    PHP Complete CRUD Application
  </nav>

  <div class="container">
    <div class="text-center mb-4">
      <h3>Edit User Information</h3>
      <p class="text-muted">Click update after changing any information</p>
    </div>

    <div class="container d-flex justify-content-center">
      <form action="" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
        
        <!-- Display existing profile image -->
        <div class="mb-3 text-center">
          <?php if (!empty($row['profile_image'])): ?>
            <img src="<?php echo $row['profile_image']; ?>" alt="Profile Image" class="rounded-circle" width="150" height="150">
          <?php else: ?>
            <img src="man.jpeg" alt="Default Profile Image" class="rounded-circle" width="150" height="150">
          <?php endif; ?>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
          <label class="form-label">Upload Profile Image:</label>
          <input type="file" class="form-control" name="profile_image">
          <!-- Hidden field to store existing image if not replaced -->
          <input type="hidden" name="existing_image" value="<?php echo $row['profile_image']; ?>">
        </div>

        <div class="row mb-3">
          <div class="col">
            <label class="form-label">First Name:</label>
            <input type="text" class="form-control" name="firstname" value="<?php echo $row['firstname'] ?>" required>
          </div>

          <div class="col">
            <label class="form-label">Last Name:</label>
            <input type="text" class="form-control" name="lastname" value="<?php echo $row['lastname'] ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email:</label>
          <input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>" required>
        </div>

        <div class="form-group mb-3">
          <label>Gender:</label>
          &nbsp;
          <input type="radio" class="form-check-input" name="gender" id="male" value="Male" <?php echo ($row["gender"] == 'male') ? "checked" : ""; ?>>
          <label for="male" class="form-input-label">Male</label>
          &nbsp;
          <input type="radio" class="form-check-input" name="gender" id="female" value="Female" <?php echo ($row["gender"] == 'female') ? "checked" : ""; ?>>
          <label for="female" class="form-input-label">Female</label>
        </div>

        <!-- New Phone and Address fields -->
        <div class="mb-3">
          <label class="form-label">Phone:</label>
          <input type="text" class="form-control" name="phone" value="<?php echo $row['phone'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Address:</label>
          <input type="text" class="form-control" name="address" value="<?php echo $row['address'] ?>" required>
        </div>

        <!-- Checkbox for newsletter subscription -->
        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" name="newsletter_subscription" id="newsletter_subscription" <?php echo ($row['newsletter_subscription'] == 1) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="newsletter_subscription">Subscribe to newsletter</label>
        </div>

        <div>
          <button type="submit" class="btn btn-success" name="submit">Update</button>
          <a href="index.php" class="btn btn-danger">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
