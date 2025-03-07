<?php
include "db_conn.php"; // Connect to your database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Retrieve form data
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    
    // Handle file upload
    $profile_image = $_FILES['profile_pic'];
    
    // Define the target directory where you want to save the uploaded image
    $target_dir = "uploads2/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($profile_image["name"]);
    $uploadOk = 1; // Flag to check if upload is successful
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is an actual image or fake image
    $check = getimagesize($profile_image["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB for example)
    if ($profile_image["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($profile_image["tmp_name"], $target_file)) {
            // File uploaded successfully, proceed to insert into database
            $sql = "INSERT INTO table2 (first_name, last_name, email, gender, profile_image
            ) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt === false) {
                die("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters (s for string)
            mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $gender, $target_file);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // If successful, redirect to the accounts.php page with a success message
                header("Location: account.php?msg=New user added successfully!");
                exit;
            } else {
                // In case of an error, display the error
                echo "Error: " . mysqli_stmt_error($stmt);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Close the connection
    mysqli_close($conn);
}
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

    <title>PHP CRUD Application - Add New User</title>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete CRUD Application - Add New User
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Add New User</h3>
            <p class="text-muted">Complete the form below to add a new user</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;" enctype="multipart/form-data">
                <!-- Profile Picture Field -->
                <div class="mb-3">
                    <label for="profile" class="form-label">Profile Picture:</label>
                    <input type="file" class="form-control" id="profile" name="profile_pic" required>
                </div>

                <!-- First and Last Name Fields -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>

                    <div class="col">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <!-- Gender Radio Buttons -->
                <div class="form-group mb-3">
                    <label>Gender:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" value="Male" required> Male
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" value="Female" required> Female
                </div>

                <!-- Submit and Cancel Buttons -->
                <div>
                    <button type="submit" class="btn btn-success" name="submit">Add User</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
