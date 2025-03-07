<?php
include "db_conn.php"; // Connect to the database

if (isset($_POST["submit"])) {
    // Get form data and sanitize inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $newsletter_subscription = isset($_POST['newsletter_subscription']) ? 1 : 0; // Checkbox for newsletter subscription

    // Initialize profile_pic variable
    $profile_pic = '';

    // File upload logic
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {
        // Get file details
        $file_name = $_FILES["profile_pic"]["name"];
        $file_tmp = $_FILES["profile_pic"]["tmp_name"];
        $file_size = $_FILES["profile_pic"]["size"];
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);
        
        // Allowed file types and size limit
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        // Validate file type and size
        if (in_array(strtolower($file_type), $allowed_types) && $file_size <= $max_size) {
            // Set upload path
            $target_dir = "uploads/";
            $new_file_name = uniqid() . "." . $file_type;
            $target_file = $target_dir . $new_file_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                $profile_pic = $target_file; // Set file path for the database
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "Invalid file type or file is too large.";
        }
    }

    // Use prepared statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO `crud` (firstname, lastname, email, gender, phone, address, profile_image, newsletter_subscription) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $gender, $phone, $address, $profile_pic, $newsletter_subscription);

    if ($stmt->execute()) {
        header("Location: index.php?msg=New user added successfully");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Add New User</title>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete CRUD Application - Register Now
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Register Now</h3>
            <p class="text-muted">Complete the form below to Register</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;" enctype="multipart/form-data">
                <!-- Profile Picture Field -->
                <div class="mb-3">
                    <label for="profile" class="form-label">Profile Picture:</label>
                    <input type="file" class="form-control" id="profile" name="profile_pic">
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

                <!-- Phone and Address Fields -->
                <div class="mb-3">
                    <label class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address:</label>
                    <input type="text" class="form-control" name="address" required>
                </div>

                <!-- Newsletter Subscription Checkbox -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="newsletter_subscription" id="newsletter">
                    <label class="form-check-label" for="newsletter">Subscribe to newsletter</label>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7L+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
