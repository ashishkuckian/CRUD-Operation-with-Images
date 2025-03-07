<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['valid'];

$sql = "SELECT * FROM `crud` WHERE Id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
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
    <title>Welcome</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #00ff5573;
            padding: 1rem;
        }
        .profile-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }
        .welcome-card {
            max-width: 500px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            margin-top: 2rem;
        }
        .profile-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }
        .user-details ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .user-details li {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .logout {
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-light justify-content-between fs-3 mb-5">
        <span class="navbar-brand ms-3">PHP CRUD Application</span>
        
        <!-- Right-aligned links and Profile Image -->
        <div class="d-flex align-items-center">
            <a href="add-new.php?show=all" class="btn btn-outline-dark me-3">Register Now</a>
            <a href="logout.php" class="btn btn-outline-dark me-3">Logout</a>
            
            <!-- Profile Icon in Navbar -->
            <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'default-profile.png'); ?>" 
                 alt="Profile Image" class="profile-icon me-3">
        </div>
    </nav>

    <div class="d-flex justify-content-center">
        <div class="welcome-card text-center">
            <!-- Header with Welcome Message -->
            <header class="mb-4">
                <h2>Welcome, <?php echo htmlspecialchars($user['firstname'] ?? 'User'); ?>!</h2>
            </header>
            
            
            <!-- User Details List -->
            <div class="user-details">
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item"><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></li>
                    <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstname'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastname'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Subscribed:</strong> <?php echo $user['newsletter_subscription'] ? 'Yes' : 'No'; ?></li>
                    <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender'] ?? 'N/A'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
