<?php
include "db_conn.php";

// Check if ID is provided
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM `table2` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the ID parameter to the statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    // Check if the deletion was successful
    if ($result) {
        header("Location: index.php?msg=Data deleted successfully");
        exit();
    } else {
        echo "Failed: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid Request: No ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>
