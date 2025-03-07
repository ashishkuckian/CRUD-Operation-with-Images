<?php
include "db_conn.php";

// Check if the search form is submitted
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = $search ? "WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR  gender LIKE '%$search%'" : '';

// SQL query to fetch data from `table2` with optional search filter
$sql = "SELECT * FROM `table2` $search_query";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
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
    <title>PHP CRUD Application</title>
</head>
<body>

<nav class="navbar navbar-light justify-content-between fs-3 mb-5" style="background-color: #00ff5573; padding: 1rem;">
  <!-- Left-aligned title -->
  <span class="navbar-brand ms-3">PHP CRUD Application</span>
    <!-- Login Link -->
    <a href="index.php?show=all" class="btn btn-outline-dark">Home</a>
  </div>
</nav>


    <div class="container">
    <?php
    // Display message if set
    if (isset($_GET["msg"])) {
        $msg = htmlspecialchars($_GET["msg"] ?? '');
        echo '<div id="successMessage" class="alert alert-warning alert-dismissible fade show" role="alert">
                ' . $msg . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        echo '<script>
            setTimeout(function() {
                var msg = document.getElementById("successMessage");
                if (msg) {
                    msg.style.display = "none";
                }
            }, 3000);
        </script>';
    }
    ?>
      <div class="d-flex justify-content-between mb-3">
      <!-- Add New Button -->
      <a href="addnew2.php" class="btn btn-dark">Add New</a>

      <!-- Search Form -->
      <form class="d-flex" action="account.php" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search users" value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>

        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Profile Picture</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td>
                            <?php if (!empty($row["profile_image"])) { ?>
                                <img src="<?php echo $row['profile_image']; ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                            <?php } else { ?>
                                <img src="path/to/default-image.jpg" alt="Default Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                            <?php } ?>
                        </td>
                        <td><?php echo $row["first_name"]; ?></td>
                        <td><?php echo $row["last_name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["gender"]; ?></td>
                        <td>
                            <a href="view2.php?id=<?php echo $row["id"]; ?>" class="link-dark"><i class="fa-solid fa-eye fs-5 me-3"></i></a>
                            <a href="edit2.php?id=<?php echo $row["id"]; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="delete2.php?id=<?php echo $row["id"]; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
