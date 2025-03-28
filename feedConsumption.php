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
<!-- head -->
<?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_head.php";?>
<body id="body">
    <div class="container">
        <!-- top navbar -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_top_navbar.php";?>
        <main>
            <div class="main__container">
                <table>
                    <thead>
                        <th>Consumed On</th>
                        <th>Quantity Consumed</th>
                        <th>Equivalent Price</th>
                        <th>Employee Responsible</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $feedConsumptionObject->viewMethod("FeedConsumption");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['ConsDate'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                    <?php 
                                        $employee = $row['Employee'];
                                        $sql = "select FirstName, LastName from Employee, FeedConsumption where Employee.Employee_ID = $employee";
                                        $query = new Database();
                                        $result = $query->connect()->query($sql);
                                        $result = mysqli_fetch_assoc($result);
                                        // print_r($result);
                                        echo $result['FirstName'].' '.$result['LastName'];
                                    ?>
                                </td>      
                                <td>
                                    <a class="edit_btn" href="feedConsumption.php?feedconsupdate=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?feedconsdelete=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["feedconsupdate"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("FeedConsumption_ID" => $id);
                        // Call the selectEmployee method that displays the record to be edited
                        $row = $feedConsumptionObject->selectMethod("FeedConsumption", $where);
                        ?>
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" value="<?php echo $row["ConsDate"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" name="Quantity" value="<?php echo $row["Quantity"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Price</label>
                                    <input type="number" step="any" name="Price" value="<?php echo $row["Price"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                        <select name="Employee" id="" required>
                                        <?php
                                            $myrow = $employeeObject->viewMethod("Employee");
                                            foreach($myrow as $row){
                                                $foreignID = $row["Employee_ID"];
                                            ?>                                    
                                            <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconsedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" name="Quantity" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Price</label>
                                    <input type="number" step="any" name="Price" value="" required>
                                </div>
                                <div class="input-group">
                                    <!-- <label for="">Employee Assigned</label>
                                    <input type="text" name="Employee" value=""> -->
                                    <label for="">Employee Assigned</label>
                                    <select name="Employee" id="" required>
                                    <?php
                                        $myrow = $feedConsumptionObject->viewMethod("Employee");
                                        foreach($myrow as $row){
                                            $foreignID = $row["Employee_ID"];
                                    ?>                                    
                                        <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconssave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
</body>
</html>