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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
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
        .inventory-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .inventory-placeholder {
            padding: 40px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            background-color: #f8f9fa;
            margin-top: 20px;
        }
        .inventory-placeholder img {
            width: 80px;
        }
        .search-bar {
            max-width: 300px;
        }
    </style>
<body>
<div class="container">
<?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_top_navbar.php";?>
    <main>
    <div class="main_container">
    <h2 class="mb-4">Inventory</h2>

    <!-- Add Inventory Button & Search -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="newInventory.php"><button class="btn btn-success">New Inventory Type</button></a>
        <input type="text" class="form-control search-bar" placeholder="Search Inventory">
    </div>

    <!-- Placeholder Section -->
    <div class="inventory-container">
        <div class="inventory-placeholder">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Empty Inventory">
            <h4 class="mt-3">Nothing yet?</h4>
            <p>Add an inventory type and they’ll show up </p>
            <p><a href="#">Getting Started Guide</a></p>
        </div>
    </div>
    </div>
    </main>
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_side_bar.php";?>
</div>
<script src="script.js"></script>
</body>
</html>
