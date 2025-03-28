<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Dummy user details (Replace with database query)
$user = [
    'name' => $_SESSION['Username'] ?? 'Guest User',
    'email' => $_SESSION['UserEmail'] ?? 'guest@example.com',
    'profile_image' => $_SESSION['ProfileImage'] ?? '../../images/doo.jpg'
];

// Dummy messages data (Fetch from database in real implementation)
$messages = [
    ['sender' => 'Pavan Kumar', 'message' => 'Just see my admin!', 'time' => '9:30 AM', 'image' => '../../assets/images/users/1.jpg'],
    ['sender' => 'Jane Doe', 'message' => 'Meeting at 3 PM.', 'time' => '8:15 AM', 'image' => '../../assets/images/users/2.jpg']
];

// Dummy notifications data (Fetch from database in real implementation)
$notifications = [
    ['title' => 'New Order', 'desc' => 'You have a new order.', 'time' => '10:00 AM', 'icon' => 'fa-shopping-cart'],
    ['title' => 'System Update', 'desc' => 'Server maintenance at 2 AM.', 'time' => '7:45 AM', 'icon' => 'fa-exclamation-triangle']
];
?>

<!-- Custom CSS -->
<link href="../../bootstrap/css/style.min.css" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Icons -->
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    

<nav class="navbar">
        <div class="nav_icon" onclick="toggleSidebar()">
          <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <div class="navbar__left">
          <a href="eggsSummary.php" onclick="toggle()">Eggs</a>
          <a href="birdsSummary.php" onclick="toggle()">Birds</a>
          <a href="feedSummary.php">Feed</a>
        </div>
        <div class="navbar__right">
          <!-- Messages Dropdown -->
          <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-envelope"></i>
                    <span class="badge badge-danger"><?php echo count($messages); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header bg-danger text-white">
                        <?php echo count($messages); ?> New Messages
                    </div>
                    <?php foreach ($messages as $msg): ?>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <img src="<?php echo $msg['image']; ?>" class="rounded-circle" width="40">
                            <div class="ml-3">
                                <strong><?php echo $msg['sender']; ?></strong>
                                <div class="small"><?php echo $msg['message']; ?></div>
                                <div class="text-muted small"><?php echo $msg['time']; ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <a class="dropdown-item text-center" href="#">See all messages</a>
                </div>
            </div>

            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    <span class="badge badge-info"><?php echo count($notifications); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header bg-primary text-white">
                        <?php echo count($notifications); ?> New Notifications
                    </div>
                    <?php foreach ($notifications as $noti): ?>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="fa <?php echo $noti['icon']; ?> text-danger"></i>
                            <div class="ml-3">
                                <strong><?php echo $noti['title']; ?></strong>
                                <div class="small"><?php echo $noti['desc']; ?></div>
                                <div class="text-muted small"><?php echo $noti['time']; ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <a class="dropdown-item text-center" href="#">Check all notifications</a>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo $user['profile_image']; ?>" class="rounded-circle" width="40">
                    <span class="ml-2"><?php echo htmlspecialchars($user['name']); ?> <i class="fa fa-chevron-down"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header bg-primary text-white text-center">
                        <img src="<?php echo $user['profile_image']; ?>" class="rounded-circle mb-2" width="60">
                        <h5 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h5>
                        <small><?php echo htmlspecialchars($user['email']); ?></small>
                    </div>
                    <a class="dropdown-item" href="adminLogin.php"><i class="fa fa-user"></i> My Profile</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-envelope"></i> Inbox</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-cog"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
      </nav>

<!-- Include Bootstrap JS and jQuery (Make sure you have Bootstrap included in your project) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
