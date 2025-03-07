<?php
// store_user.php

// Include the database connection
include "db_conn.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    
    // Handle file upload
    $profile_image = $_FILES['profile_image']; // Retrieve the file array

    // Define the target directory where you want to save the uploaded image
    $target_dir = "uploads2/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($profile_image["name"]);
    $uploadOk = 1; // Flag to check if upload is successful
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is a actual image or fake image
    $check = getimagesize($profile_image["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB for example)
    if ($profile_image["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
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
            $sql = "INSERT INTO table2 (first_name, last_name, email, gender, profile_pic) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt === false) {
                die("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters (s for string, since all are strings)
            mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $gender, $target_file);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // If successful, redirect to the accounts.php page with a success message
                header("Location: account.php?msg=New account added successfully!");
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
