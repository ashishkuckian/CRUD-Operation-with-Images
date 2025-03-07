<?php
include "db_conn.php"; // Include database connection

// Get sort column and direction from URL
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // Default sort column
$sort_direction = isset($_GET['dir']) ? $_GET['dir'] : 'ASC'; // Default sort direction

// Check if the search form is submitted
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Modify the SQL query to search for matching records
    $sql = "SELECT * FROM `crud` WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%' ORDER BY $sort_column $sort_direction";
} else {
    // Default query to fetch all records with sorting
    $sql = "SELECT * FROM `crud` ORDER BY $sort_column $sort_direction"; 
}

// Execute the query
$result = mysqli_query($conn, $sql);

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

    <title>Users List</title>

    <style>
        .sort-arrow {
            color: white;
        }

        /* Styles for Yes/No colors */
        .yes-text {
            color: green;
            font-weight: bold;
        }

        .no-text {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-between fs-3 mb-5" style="background-color: #00ff5573;">
        <!-- Left-aligned title -->
        <span class="navbar-brand ms-3">PHP CRUD Application</span>

        <!-- Right-aligned links -->
        <div class="d-flex">
            <a href="user.php" class="btn btn-outline-dark me-3">Users</a>
            <a href="account.php" class="btn btn-outline-dark">Accounts</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <!-- Add New Button -->
            <a href="add-new.php" class="btn btn-dark">Add New</a>

            <!-- Search Form -->
            <form class="d-flex" action="user.php" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Search users" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>

        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">
                        <a href="?sort=id&dir=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
                            ID
                            <?php if ($sort_column == 'id') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
                        </a>
                    </th>
                    <th scope="col">Profile Picture</th>
                    <th scope="col">
                        <a href="?sort=firstname&dir=<?php echo ($sort_column == 'firstname' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
                            First Name
                            <?php if ($sort_column == 'firstname') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
                        </a>
                    </th>
                    <th scope="col">
                        <a href="?sort=lastname&dir=<?php echo ($sort_column == 'lastname' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
                            Last Name
                            <?php if ($sort_column == 'lastname') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
                        </a>
                    </th>
                    <th scope="col">
                        <a href="?sort=email&dir=<?php echo ($sort_column == 'email' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
                            Email
                            <?php if ($sort_column == 'email') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
                        </a>
                    </th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Subscribed</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Set profile image if available or use default placeholder
                        $profile_image = !empty($row["profile_image"]) && file_exists($row["profile_image"]) ? $row["profile_image"] : 'man.jpeg';
                ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><img src="<?php echo $profile_image; ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"></td>
                            <td><?php echo htmlspecialchars($row["firstname"]); ?></td>
                            <td><?php echo htmlspecialchars($row["lastname"]); ?></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td>
                            <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                            <td><?php echo htmlspecialchars($row["address"]); ?></td>
                            <td class="<?php echo (isset($row["subscribed"]) && $row["subscribed"]) ? 'yes-text' : 'no-text'; ?>">
                                <?php echo (isset($row["subscribed"]) && $row["subscribed"]) ? 'Yes' : 'No'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["gender"]); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    echo '<tr><td colspan="9">No users found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybT3v53hz8p8LvC5Hh2cFqFOhRWg8zrGrwU8A0Hc7Cqkz4TKP" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"></script>
</body>

</html>
