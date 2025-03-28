<?php
    include_once 'database.php';
    class CrudOperation extends Database{
        // Insertion method 
        public function insertionMethod($table, $fields){
            // "INSERT INTO table_name (, , ) VALUES ('','')";
            $sql = "";
            $sql .="INSERT INTO " . $table;
            $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
            $sql .= "('".implode("','", array_values($fields))."')";
            // Execute the query
            $query = $this->connect()->query($sql);
            if($query){
                return true;
            }
        }
        // Method to Fetching data from the database
        public function viewMethod($table){
            // Writing the query
            $sql = "SELECT * FROM " . $table;
            $array = array();
            // Query execution
            $query = $this->connect()->query($sql);
            while($row = mysqli_fetch_assoc($query)){
                $array[] = $row;
            }
            return $array;
        }
        // Method to edit data 
        public function selectMethod($table,$where){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                // id = '5' AND FirstName = 'somename'
                // Concatenate the condition to dynamically generate id when edit button is clicked
                $condition .= $key . "='" . $value . "' AND ";
            }
            // Remove the last 5 characters from the condition
            $condition = substr($condition, 0, -5);
            // SELECT query
            $sql .= "SELECT * FROM " .$table. " WHERE " . $condition;
            // Execute SELECT query
            $query = $this->connect()->query($sql);
            $row = mysqli_fetch_array($query);
            return $row;           
        }
        // Method to update data
        public function updateMethod($table, $where, $fields){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                // id = '5' AND FirstName = 'somename'
                // Concatenate the condition to dynamically generate id when edit button is clicked
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            foreach($fields as $key => $value){
                // UPDATE table SET FirstName = '', LastName = '' WHERE id = '';
                $sql .= $key . "='" . $value . "', ";
            }
            // Remove extra , and space from the sql query above
            $sql = substr($sql, 0, -2);
            // Full/concatenated query to be executed
            $sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
            // Execute the query
            if($query = $this->connect()->query($sql)){
                return true;
            }
        }
        // Deletion method
        public function deleteMethod($table, $where){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = "DELETE FROM " . $table . " WHERE " . $condition;
            if($query = $this->connect()->query($sql)){
                return true;
            }
        }
    }


    $employeeObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["save"])){
        $myArray = array(
            "FirstName" => $_POST["FirstName"],
            "LastName" => $_POST["LastName"],
            "Phone" => $_POST["Phone"],
            "Job" => $_POST["Job"],
            "Salary" => $_POST["Salary"]
        );
        // Call the insertion method to add record to the database
        if($employeeObject->insertionMethod("Employee", $myArray)){
            header("location: ../payroll.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["edit"])){
        $id = $_POST["id"];
        $where = array("Employee_ID" => $id);
        $myArray = array(
            "FirstName" => $_POST["FirstName"],
            "LastName" => $_POST["LastName"],
            "Phone" => $_POST["Phone"],
            "Job" => $_POST["Job"],
            "Salary" => $_POST["Salary"]
        );
        if($employeeObject->updateMethod("Employee", $where, $myArray)){
            header("location: ../payroll.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["delete"])){
        $id = $_GET["id"] ?? null;
        $where = array("Employee_ID" => $id);
        if($employeeObject->deleteMethod("Employee", $where)){
            header("location: ../payroll.php?msg=Record deleted successfully!");
        }
    }

    // FEED CONSUMPTION

    // New object to call methods on FeedConsumption table
    $feedConsumptionObject = new CrudOperation();

    if(isset($_POST["feedconssave"])){
        $foreignID = $_POST["Employee"];
        $myArray = array(
            "ConsDate" => $_POST["ConsDate"],
            "Quantity" => $_POST["Quantity"],
            "Price" => $_POST["Price"],
            "Employee" => $foreignID
        );
        // Call the insertion method to add record to the database
        if($feedConsumptionObject->insertionMethod("FeedConsumption", $myArray)){
            header("location: ../feedConsumption.php?msg=Insertion was successfull!");
        };
    }

    // Handle the edit button for record editing
    if(isset($_POST["feedconsedit"])){
        $id = $_POST["id"];
        $where = array("FeedConsumption_ID" => $id);
        $myArray = array(
            "ConsDate" => $_POST["ConsDate"],
            "Quantity" => $_POST["Quantity"],
            "Price" => $_POST["Price"],
            "Employee" => $_POST["Employee"]
        );
        if($feedConsumptionObject->updateMethod("FeedConsumption", $where, $myArray)){
            header("location: ../feedConsumption.php?msg=Updated Successfully!");
        }
    }
    // Check if delete button was triggered
    if(isset($_GET["feedconsdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("FeedConsumption_ID" => $id);
        if($feedConsumptionObject->deleteMethod("FeedConsumption", $where)){
            header("location: ../feedConsumption.php?msg=Record deleted successfully!");
        }
    }

    // FEED PURCHASE

    // Create object for feed purchase
    $feedPurchaseObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["feedpurchsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "Quantity" => $_POST["Quantity"],
            "Price" => $_POST["Price"]
        );
        // Call the insertion method to add record to the database
        if($feedPurchaseObject->insertionMethod("FeedPurchase", $myArray)){
            header("location: ../feedPurchase.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["feedpurchedit"])){
        $id = $_POST["id"];
        $where = array("FeedPurchase_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "Quantity" => $_POST["Quantity"],
            "Price" => $_POST["Price"]
        );
        if($feedPurchaseObject->updateMethod("FeedPurchase", $where, $myArray)){
            header("location: ../feedPurchase.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["feedpurchdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("FeedPurchase_ID" => $id);
        if($feedPurchaseObject->deleteMethod("FeedPurchase", $where)){
            header("location: ../feedPurchase.php?msg=Record deleted successfully!");
        }
    }

    // BIRDS PURCHASE

    // Create object for feed purchase
    $birdsPurchaseObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["birdspurchsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfBirds" => $_POST["NumberOfBirds"],
            "Price" => $_POST["Price"]
        );
        // Call the insertion method to add record to the database
        if($birdsPurchaseObject->insertionMethod("BirdsPurchase", $myArray)){
            header("location: ../birdsPurchase.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["birdspurchedit"])){
        $id = $_POST["id"];
        $where = array("BirdsPurchase_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfBirds" => $_POST["NumberOfBirds"],
            "Price" => $_POST["Price"]
        );
        if($birdsPurchaseObject->updateMethod("BirdsPurchase", $where, $myArray)){
            header("location: ../birdsPurchase.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["birdspurchdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("BirdsPurchase_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("BirdsPurchase", $where)){
            header("location: ../birdsPurchase.php?msg=Record deleted successfully!");
        }
    }

    // BIRDS MORTALITY

    // Create object for feed purchase
    $birdsMortalityObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["birdsmortsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "Deaths" => $_POST["Deaths"]
        );
        // Call the insertion method to add record to the database
        if($birdsMortalityObject->insertionMethod("BirdsMortality", $myArray)){
            $_SESSION['msg'] = "Added new record!";
            header("location: ../birdsMortality.php");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["birdsmortedit"])){
        $id = $_POST["id"];
        $where = array("BirdsMortality_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "Deaths" => $_POST["Deaths"]
        );
        if($birdsMortalityObject->updateMethod("BirdsMortality", $where, $myArray)){
            header("location: ../birdsMortality.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["birdsmortdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("BirdsMortality_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("BirdsMortality", $where)){
            header("location: ../birdsMortality.php?msg=Record deleted successfully!");
        }
    }

    // LIVESTOCK PURCHASE
    
    // Create object for feed purchase
    $livestockPurchaseObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["livestockpurchsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfLivestock" => $_POST["NumberOfLivestock"],
            "Price" => $_POST["Price"]
        );
        // Call the insertion method to add record to the database
        if($livestockPurchaseObject->insertionMethod("LivestockPurchase", $myArray)){
            header("location: ../livestockPurchase.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["livestockpurchedit"])){
        $id = $_POST["id"];
        $where = array("LivestockPurchase_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfLivestock" => $_POST["NumberOfLivestock"],
            "Price" => $_POST["Price"]
        );
        if($livestockPurchaseObject->updateMethod("LivestockPurchase", $where, $myArray)){
            header("location: ../livestockPurchase.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["livestockpurchdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("LivestockPurchase_ID" => $id);
        if($livestockPurchaseObject->deleteMethod("LivestockPurchase", $where)){
            header("location: ../livestockPurchase.php?msg=Record deleted successfully!");
        }
    }

    // LIVESTOCK MORTALITY

    // Create object for feed purchase
    $livestockMortalityObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["livestockmortsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "Deaths" => $_POST["Deaths"]
        );
        // Call the insertion method to add record to the database
        if($livestockMortalityObject->insertionMethod("LivestockMortality", $myArray)){
            $_SESSION['msg'] = "Added new record!";
            header("location: ../livestockMortality.php");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["livestockmortedit"])){
        $id = $_POST["id"];
        $where = array("LivestockMortality_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "Deaths" => $_POST["Deaths"]
        );
        if($livestockMortalityObject->updateMethod("LivestockMortality", $where, $myArray)){
            header("location: ../livestockMortality.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["livestockmortdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("LivestockMortality_ID" => $id);
        if($livestockPurchaseObject->deleteMethod("LivestockMortality", $where)){
            header("location: ../livestockMortality.php?msg=Record deleted successfully!");
        }
    }

    // EGG SALES

    // Create object for egg sales
    $salesObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["salessave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfEggs" => $_POST["NumberOfEggs"],
            "Revenue" => $_POST["Revenue"]
        );
        // Call the insertion method to add record to the database
        if($salesObject->insertionMethod("Sales", $myArray)){
            header("location: ../sales.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["salesedit"])){
        $id = $_POST["id"];
        $where = array("Sales_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfEggs" => $_POST["NumberOfEggs"],
            "Revenue" => $_POST["Revenue"]
        );
        if($salesObject->updateMethod("Sales", $where, $myArray)){
            header("location: ../sales.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["salesdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("Sales_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("Sales", $where)){
            header("location: ../sales.php?msg=Record deleted successfully!");
        }
    }

    // EGG PRODUCTION

    // Create object for egg production
    $productionObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["productionsave"])){
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfEggs" => $_POST["NumberOfEggs"]
        );
        // Call the insertion method to add record to the database
        if($productionObject->insertionMethod("Production", $myArray)){
            header("location: ../production.php?msg=Insertion was successfull!");
        };
    }
    // Handle the edit button for record editing
    if(isset($_POST["productionedit"])){
        $id = $_POST["id"];
        $where = array("Production_ID" => $id);
        $myArray = array(
            "Date" => $_POST["Date"],
            "NumberOfEggs" => $_POST["NumberOfEggs"]
        );
        if($productionObject->updateMethod("Production", $where, $myArray)){
            header("location: ../production.php?msg=Updated Successfully!");
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["productiondelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("Production_ID" => $id);
        if($productionObject->deleteMethod("Production", $where)){
            header("location: ../production.php?msg=Record deleted successfully!");
        }
    }

    // INSIGHTS

    // Returning the total number of birds purchased
    $databaseObject = new Database();
    $query = "SELECT SUM(NumberOfBirds) AS sum FROM `BirdsPurchase`"; 
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfBirds = $row['sum'];
    };

     // Returning the total number of livestock purchased
     $query = "SELECT SUM(NumberOfLivestock) AS sum FROM `LivestockPurchase`"; 
     $result = $databaseObject->connect()->query($query);
     while($row = mysqli_fetch_assoc($result)){
         $totalNumberOfLivestock = $row['sum'];
     }

    // Returning the total number of eggs
    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Production`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfEggs = $row['sum'];
    }

    // Returning the mortality rate

    $query = "SELECT SUM(Deaths) AS sum FROM `BirdsMortality`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalDeaths = $row['sum'];
    }

    if($totalDeaths <= $totalNumberOfBirds){
        $mortalityRate = round($totalDeaths / $totalNumberOfBirds * 100 , 1);
        $totalNumberOfBirds = $totalNumberOfBirds - $totalDeaths;
    }else{
        $mortalityRate = 0;
    }
    $remainingBirds = $totalNumberOfBirds - $totalDeaths;
    
    // Returning the total number of wages
    $query = "SELECT SUM(Salary) AS sum FROM `Employee`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalWages = $row['sum'];
    }

    // Returning the livestock mortality rate

    $query = "SELECT SUM(Deaths) AS sum FROM `LivestockMortality`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalDeaths = $row['sum'];
    }

    if($totalDeaths <= $totalNumberOfLivestock){
        $MortalityRate = round($totalDeaths / $totalNumberOfLivestock * 100 , 1);
        $totalNumberOfLivestock = $totalNumberOfLivestock - $totalDeaths;
    }else{
        $MortalityRate = 0;
    }
    $remainingLivestock = $totalNumberOfLivestock - $totalDeaths;
    
    // Returning the total number of wages
    $query = "SELECT SUM(Salary) AS sum FROM `Employee`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalWages = $row['sum'];
    }

    // Returning total revenue
    $query = "SELECT SUM(Revenue) AS sum FROM `Sales`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $sales = $row['sum'];
    }

    // Returning remaining feed in the stock
    $query = "SELECT SUM(Quantity) AS sum FROM `FeedPurchase`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalFeedPurchased = $row['sum'];
    }

    $query = "SELECT SUM(Quantity) AS sum FROM `FeedConsumption`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalFeedConsumed = $row['sum'];
    }
    $remainingFeed = $totalFeedPurchased - $totalFeedConsumed;

    // Returning number of eggs available for sale
    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Production`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalEggsProduced = $row['sum'];
    }

    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Sales`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalEggsSold = $row['sum'];
    }
    $remainingEggs = $totalEggsProduced - $totalEggsSold;

    // Getting the total number of employees working in the farm
    $query = "SELECT COUNT(*) AS sum FROM `Employee`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfEmployees = $row['sum'];
    }

$servername = "localhost"; // Change this to your database server
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "phpmyadmin"; // Change this to your database name

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $modelYear = $_POST['model_year'] ?? '';
    $plateNumber = $_POST['plate_number'] ?? '';
    $serialNumber = $_POST['serial_number'] ?? '';
    $engine = $_POST['engine'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $trackUsage = $_POST['track_usage'] ?? '';
    $currentUsage = $_POST['current_usage'] ?? 0;
    $serviceReminder = $_POST['service_reminder'] ?? '';
    $emailAlerts = $_POST['email_alerts'] ?? '';
    $usageCost = $_POST['usage_cost'] ?? '';
    $serviceManual = $_POST['service_manual'] ?? '';
    $leasedPurchased = $_POST['leased_purchased'] ?? '';
    $dateAcquired = $_POST['date_acquired'] ?? '';
    $purchasePrice = $_POST['purchase_price'] ?? '';
    $insured = isset($_POST['insured']) ? 'Yes' : 'No';
    $estimatedValue = $_POST['estimated_value'] ?? '';
    $description = $_POST['description'] ?? '';

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO equipment (name, type, status, brand, model, model_year, plate_number, serial_number, engine, transmission, track_usage, current_usage, service_reminder, email_alerts, usage_cost, service_manual, leased_purchased, date_acquired, purchase_price, insured, estimated_value, description) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssssssssssssssssssss", 
        $name, $type, $status, $brand, $model, $modelYear, $plateNumber, $serialNumber, $engine, $transmission, 
        $trackUsage, $currentUsage, $serviceReminder, $emailAlerts, $usageCost, $serviceManual, 
        $leasedPurchased, $dateAcquired, $purchasePrice, $insured, $estimatedValue, $description
    );

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to index.php after successful submission
        header("Location: equipment.php?msg=Record inserted successfully!");
        exit(); // Ensure no further execution
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php';
    
    // Initialize variables
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
    
    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO events (
            title, 
            start_datetime, 
            end_datetime, 
            all_day, 
            description, 
            attachment_path, 
            associated_to, 
            assigned_to, 
            repeats, 
            created_at
        ) VALUES (
            :title, 
            :start_datetime, 
            :end_datetime, 
            :all_day, 
            :description, 
            :attachment_path, 
            :associated_to, 
            :assigned_to, 
            :repeats, 
            NOW()
        )");
        
        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':start_datetime', $start_datetime);
        $stmt->bindParam(':end_datetime', $end_datetime);
        $stmt->bindParam(':all_day', $all_day, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':attachment_path', $attachment_path);
        $stmt->bindParam(':associated_to', $associated_to);
        $stmt->bindParam(':assigned_to', $assigned_to);
        $stmt->bindParam(':repeats', $repeats);
        
        // Execute the statement
        $stmt->execute();
        
        // Set success message
        $success_message = "Event created successfully!";
        
    } catch (PDOException $e) {
        $error_message = "Error creating event: " . $e->getMessage();
    }
    
    // Redirect back to form with messages
    header("Location: event_form.php?" . 
        (isset($success_message) ? "success=" . urlencode($success_message) : "error=" . urlencode($error_message)));
    exit();
}
//map

?>