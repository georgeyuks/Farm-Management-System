<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
include 'includes/database.php';
include 'includes/action.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_head.php";?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .main_container {
            max-width: 600px;
            margin: auto;
            padding-top: 100px;
        }
        main {
            background: #f3f4f6;
            grid-area: main;
            overflow-y: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .button {
            background-color: green;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .search-bar {
            padding: 8px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .equipment-box {
            border: 2px dashed #ccc;
            text-align: center;
            padding: 50px;
            color: #555;
        }
        .equipment-box img {
            width: 50px;
            margin-bottom: 10px;
        }
    </style>
<body>
    <div class="container">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_top_navbar.php";?>
    <main>
        <div class="main_container">
            <div class="header">
            <h2>Task</h2>
            <div>
            <a href="newTask.php"><button class="button">New Task</button></a>
                <button class="button">...</button>
            </div>
        </div>
        <div class="search-filter">
            <input type="text" class="search-bar" placeholder="Search Equipment">
            <button class="button">Filter</button>
        </div>
        <div class="equipment-box">
            <img src="tractor-icon.png" alt="Tractor">
            <p><em>No equipment yet?</em></p>
            <p>Add your equipment and it will show up here</p>
            <p>Need help? Check out this <a href="#">Getting Started Guide</a>.</p>
        </div>
        </div>
    </main>
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
</body>
</html>
