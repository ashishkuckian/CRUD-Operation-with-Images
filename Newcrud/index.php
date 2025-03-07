<?php
include "db_conn.php";

// Number of records per page
$limit = 5;

// Get the current page or set a default
if (isset($_GET['page']) && $_GET['page'] > 0) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

// Get sort column and direction from URL
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // Default sort column
$sort_direction = isset($_GET['dir']) ? $_GET['dir'] : 'ASC'; // Default sort direction

// Calculate the starting row for the query
$start = ($page - 1) * $limit;

// Check if the search form is submitted
$search = '';
if (isset($_GET['search'])) {
  $search = $_GET['search'];
  // Modify the SQL query to search for matching records
  $sql = "SELECT * FROM `crud` WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR address LIKE '%$search%' OR gender LIKE '%$search%' ORDER BY $sort_column $sort_direction LIMIT $start, $limit";
  $count_sql = "SELECT COUNT(*) FROM `crud` WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR address LIKE '%$search%' OR gender LIKE '%$search%'";
} else {
  // Default query to fetch limited records with sorting
  $sql = "SELECT * FROM `crud` ORDER BY $sort_column $sort_direction LIMIT $start, $limit";
  $count_sql = "SELECT COUNT(*) FROM `crud`"; // For counting total records for pagination
}

$result = mysqli_query($conn, $sql);
$total_result = mysqli_query($conn, $count_sql);
$total_records = mysqli_fetch_array($total_result)[0]; // Get total record count
$total_pages = ceil($total_records / $limit); // Calculate total pages
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

  <title>PHP CRUD Application with Pagination</title>

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
<nav class="navbar navbar-light justify-content-between fs-3 mb-5" style="background-color: #00ff5573; padding: 1rem;">
  <!-- Left-aligned title -->
  <span class="navbar-brand ms-3">PHP CRUD Application</span>

  <!-- Right-aligned links -->
  <div class="d-flex">
    <!-- Users Link: When clicked, show all users -->
    <a href="user.php?show=all" class="btn btn-outline-dark me-4">Users</a>

    <!-- Accounts Link: Link to add-new.php for adding a new account -->
    <a href="account.php" class="btn btn-outline-dark me-4">Accounts</a>

    <!-- Login Link -->
    <a href="login.php?show=all" class="btn btn-outline-dark">Login</a>
  </div>
</nav>





  <div class="container">
    <?php
    // Check for message and display it
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div id="successMessage" class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . htmlspecialchars($msg ?? '') . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
      echo '<script>
          setTimeout(function() {
            var msg = document.getElementById("successMessage");
            if (msg) {
              msg.style.display = "none";
            }
          }, 3000); // Hide message after 3 seconds
        </script>';
    }
    ?>

    <div class="d-flex justify-content-between mb-3">
      <!-- Add New Button -->
      <a href="add-new.php" class="btn btn-dark">Register</a>

      <!-- Search Form -->
      <form class="d-flex" action="index.php" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search users" value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>

    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th scope="col">
            <a href="?page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search); ?>&sort=id&dir=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
              ID
              <?php if ($sort_column == 'id') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
            </a>
          </th>
          <th scope="col">Profile Picture</th>
          <th scope="col">
            <a href="?page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search); ?>&sort=firstname&dir=<?php echo ($sort_column == 'firstname' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
              First Name
              <?php if ($sort_column == 'firstname') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
            </a>
          </th>
          <th scope="col">
            <a href="?page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search); ?>&sort=lastname&dir=<?php echo ($sort_column == 'lastname' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
              Last Name
              <?php if ($sort_column == 'lastname') echo ($sort_direction == 'ASC') ? '<i class="fa-solid fa-arrow-up sort-arrow"></i>' : '<i class="fa-solid fa-arrow-down sort-arrow"></i>'; ?>
            </a>
          </th>
          <th scope="col">
            <a href="?page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search); ?>&sort=email&dir=<?php echo ($sort_column == 'email' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-white">
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
              <td><?php echo htmlspecialchars($row["id"] ?? ''); ?></td>
              <td>
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
              </td>
              <td><?php echo htmlspecialchars($row["firstname"] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row["lastname"] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row["email"] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row["phone"] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row["address"] ?? ''); ?></td>
              <td>
                <span class="subscription-text <?php echo $row["newsletter_subscription"] ? 'yes-text' : 'no-text'; ?>">
                  <?php echo $row["newsletter_subscription"] ? 'Yes' : 'No'; ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($row["gender"] ?? ''); ?></td>
              <td>
                <a href="view.php?id=<?php echo $row["id"] ?? ''; ?>" class="link-dark"><i class="fa-solid fa-eye fs-5 me-3"></i></a>
                <a href="edit.php?id=<?php echo $row["id"] ?? ''; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                <a href="delete.php?id=<?php echo $row["id"] ?? ''; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
              </td>
            </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='10'>No users found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
          <a class="page-link" href="<?php if ($page > 1) echo "?page=" . ($page - 1) . "&search=" . htmlspecialchars($search) . "&sort=" . htmlspecialchars($sort_column) . "&dir=" . htmlspecialchars($sort_direction); ?>">Previous</a>
        </li>
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
          echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '&search=' . htmlspecialchars($search) . '&sort=' . htmlspecialchars($sort_column) . '&dir=' . htmlspecialchars($sort_direction) . '">' . $i . '</a></li>';
        }
        ?>
        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
          <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1) . "&search=" . htmlspecialchars($search) . "&sort=" . htmlspecialchars($sort_column) . "&dir=" . htmlspecialchars($sort_direction); ?>">Next</a>
        </li>
      </ul>
    </nav>
  </div>

  <!-- Bootstrap JS and Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzU6WIl2wP6VR8LgG7Yuu3rI2Hf6UgghecP7HA6NYJc6" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuGkpS5E1CWV1jK8LoBe1RIqdKq20zVAGIuuE9IebhndyF2yJtHVd+aCZmO9zT7" crossorigin="anonymous"></script>

  <!-- JavaScript for dynamic checkbox coloring -->
  <script>
    // Function to update the Yes/No text color based on checkbox state
    function updateSubscriptionText(checkbox) {
      const textElement = checkbox.nextElementSibling;
      if (checkbox.checked) {
        textElement.textContent = 'Yes';
        textElement.classList.add('yes-text');
        textElement.classList.remove('no-text');
      } else {
        textElement.textContent = 'No';
        textElement.classList.add('no-text');
        textElement.classList.remove('yes-text');
      }
    }

    // Get all the subscription checkboxes
    const subscriptionCheckboxes = document.querySelectorAll('.subscription-checkbox');

    // Initialize the colors based on their current state
    subscriptionCheckboxes.forEach((checkbox) => {
      updateSubscriptionText(checkbox);

      // Add a change event listener to dynamically change the text color when clicked
      checkbox.addEventListener('change', function () {
        updateSubscriptionText(checkbox);
      });
    });
  </script>

</body>

</html>
