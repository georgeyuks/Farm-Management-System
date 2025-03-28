<?php
// Database configuration
$host = 'localhost';
$dbname = 'phpmyadmin';
$username = 'root';
$password = '';

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $title = $_POST['title'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $all_day = isset($_POST['all_day']) ? 1 : 0;
        $end_date = $_POST['end_date'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $description = $_POST['description'] ?? '';
        $associated_to = $_POST['associated_to'] ?? '';
        $assigned_to = $_POST['assigned_to'] ?? '';
        $repeats = $_POST['repeats'] ?? 'none';
        
        // Handle file upload
        $attachment_path = '';
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = basename($_FILES['attachment']['name']);
            $target_path = $upload_dir . uniqid() . '_' . $file_name;
            
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_path)) {
                $attachment_path = $target_path;
            }
        }
        
        // Combine date and time
        $start_datetime = $start_date . ' ' . $start_time;
        $end_datetime = $end_date . ' ' . $end_time;
        
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO events (
            title, start_datetime, end_datetime, all_day, description,
            attachment_path, associated_to, assigned_to, repeats, created_at
        ) VALUES (
            :title, :start_datetime, :end_datetime, :all_day, :description,
            :attachment_path, :associated_to, :assigned_to, :repeats, NOW()
        )");
        
        $stmt->execute([
            ':title' => $title,
            ':start_datetime' => $start_datetime,
            ':end_datetime' => $end_datetime,
            ':all_day' => $all_day,
            ':description' => $description,
            ':attachment_path' => $attachment_path,
            ':associated_to' => $associated_to,
            ':assigned_to' => $assigned_to,
            ':repeats' => $repeats
        ]);
        
        header("Location: task.php");
        exit();
    } catch (PDOException $e) {
        $error_message = "Error creating event: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* ... (rest of your CSS styles) ... */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Event</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="section">
                <h2>Title</h2>
                <input type="text" name="title" placeholder="Example: Vet Appointment" required
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>

            <div class="section">
                <h2>Starting</h2>
                <input type="date" name="start_date" 
                       value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '2025-03-17'; ?>">
                <input type="time" name="start_time" 
                       value="<?php echo isset($_POST['start_time']) ? htmlspecialchars($_POST['start_time']) : '19:00'; ?>">
                <label>
                    <input type="checkbox" name="all_day" value="1" 
                           <?php echo isset($_POST['all_day']) ? 'checked' : ''; ?>> All Day
                </label>
            </div>

            <div class="section">
                <h2>Ending</h2>
                <input type="date" name="end_date" 
                       value="<?php echo isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : '2025-03-17'; ?>">
                <input type="time" name="end_time" 
                       value="<?php echo isset($_POST['end_time']) ? htmlspecialchars($_POST['end_time']) : '20:00'; ?>">
            </div>

            <div class="section">
                <h2>Description</h2>
                <textarea name="description" rows="4" placeholder="Add some details or a description..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="section">
                <label>Attachment</label>
                <input type="file" name="attachment">
                
                <label>Associated To</label>
                <div class="search-container">
                    <input type="text" name="associated_to" placeholder="Find Animal, Equipment"
                           value="<?php echo isset($_POST['associated_to']) ? htmlspecialchars($_POST['associated_to']) : ''; ?>">
                </div>
                
                <label>Assigned To</label>
                <select name="assigned_to">
                    <option value="Don" <?php echo (isset($_POST['assigned_to']) && $_POST['assigned_to'] === 'Don') ? 'selected' : ''; ?>>Don</option>
                    <!-- Add more options if needed -->
                </select>
                
                <label>Repeats</label>
                <select name="repeats">
                    <option value="none" <?php echo (isset($_POST['repeats']) && $_POST['repeats'] === 'none') ? 'selected' : ''; ?>>Does not repeat</option>
                    <option value="daily" <?php echo (isset($_POST['repeats']) && $_POST['repeats'] === 'daily') ? 'selected' : ''; ?>>Daily</option>
                    <option value="weekly" <?php echo (isset($_POST['repeats']) && $_POST['repeats'] === 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                    <option value="monthly" <?php echo (isset($_POST['repeats']) && $_POST['repeats'] === 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                    <option value="yearly" <?php echo (isset($_POST['repeats']) && $_POST['repeats'] === 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit">Create</button>
                <button type="button" onclick="window.location.href='task.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>