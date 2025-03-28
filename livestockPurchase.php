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
            <div class="main_container">
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Number of Livestock</th>
                        <th>Price</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $livestockPurchaseObject->viewMethod("LivestockPurchase");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['NumberOfLivestock'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                    <a class="edit_btn" href="livestockPurchase.php?livestockpurchupdate=1&id=<?php echo $row["LivestockPurchase_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?livestockpurchdelete=1&id=<?php echo $row["LivestockPurchase_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["livestockpurchupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("LivestockPurchase_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $livestockPurchaseObject->selectMethod("LivestockPurchase", $where);
                        ?>
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input id="BPDate" type="date" name="Date" value="<?php echo $row["Date"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Number of Livestock</label>
                                    <input type="number" step="any" name="NumberOfLivestock" value="<?php echo $row["NumberOfLivestock"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Price</label>
                                    <input type="number" step="any" name="Price" value="<?php echo $row["Price"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="livestockpurchedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>  
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input id="BPDate" type="date" name="Date" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Number of Livestock</label>
                                    <input type="number" step="any" name="NumberOfLivestock" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Price</label>
                                    <input type="number" step="any" name="Price" value="" required>
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="livestockpurchsave" class="btn">Save</button>
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