<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    include 'includes/database.php';
    include 'includes/action.php';

    $sql = "SELECT * FROM Employee";
    $res = $databaseObject->connect()->query($sql);
    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest'; // Default to guest
?>
<!DOCTYPE html>
<html lang="en">
<!-- head -->
<?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_head.php";?>
<script>
    var userRole = "<?php echo $user_role; ?>"; // Pass PHP role to JavaScript
</script>
<body id="body">
    <div class="container">
        <!-- top navbar -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_top_navbar.php";?>
        <main>
            <div class="main__container">
                <!-- dashboard title and greetings -->
                <div class="main__title">
                    <!-- <img src="images/hello.svg" alt=""> -->
                    <div class="main__greeting">
                        <h1>Hello<?php echo ', ' . $_SESSION["username"] . '.';?></h1>
                        <p>Welcome to your dashboard</p>
                    </div>
                </div>
                <!-- dashboard title ends here -->

                <!-- Cards for displaying CRUD insights -->
                <div class="main__cards">
                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Birds</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfBirds;
                                ?>
                            </span>
                        </div>
                    </div>

                    <!-- Cards for displaying CRUD insights -->
                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Livestock</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfLivestock;
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">LivestockMortality Rate</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $MortalityRate . '%';
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">BirdsMortality Rate</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $mortalityRate . '%';
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Eggs</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfEggs;
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Employees</p>
                            <!-- <span class="font-bold text-title">578</span> -->
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfEmployees;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- End of cards for displaying CRUD insights -->
                <!-- Start of charts for displaying CRUD insights -->
                <div class="charts">
                    <div class="charts__left">
                        <div class="charts__left__title">
                            <div>
                                <h1>Payroll Visualtion</h1>
                                <p>Job titles and their respective salaries</p>
                            </div>
                            <i class="fa fa-money" aria-hidden="true"></i>
                        </div>
                        <div id="piechart_3d" style="width: 450px; height: 250px;"></div>
                    </div>

                    <div class="charts__right">
                        <div class="charts__right__title">
                            <div>
                                <h1>Stats</h1>
                                <p>Statistics of different categories</p>
                            </div>
                            <i class="fa fa-money" aria-hidden="true"></i>
                        </div>

                        <div class="charts__right__cards">
                            <div class="card1">
                            <h1>Total Wages</h1>
                            <p><?php echo 'GHC '. $totalWages; ?></p>
                        </div>

                        <div class="card2">
                            <h1>Sales</h1>
                            <p><?php echo 'GHC '. $sales; ?></p>
                        </div>

                        <div class="card3">
                            <h1>Remaining Feed</h1>
                            <?php
                                if($remainingFeed > 0){ ?>
                                    <p><?php echo $remainingFeed . ' Kg'; ?></p>
                                <?php
                                }else{?>
                                    <p style="color: red;"><?php echo 'Please refill the feed stock!'; ?></p>
                                <?php
                                }
                                ?>
                        </div>

                        <div class="card4">
                            <h1>Eggs Left</h1>
                            <?php
                                if($remainingEggs > 0){ ?>
                                    <p><?php echo $remainingEggs; ?></p>
                                <?php
                                }else{?>
                                    <p style="color: red;"><?php echo 'Nothing to sell!'; ?></p>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </div>
                <!-- End of charts for displaying CRUD insights -->
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/partials/_side_bar.php";?>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Employee', 'Salary'],
                <?php
                    while($row=$res->fetch_assoc()){
                        echo "['".$row['Job']."',".$row['Salary']."],";
                    }
                ?>   
            ]);
            var options = {
                title: 'Titles and Salaries',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>
    <script src="script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (userRole === "guest") {
                document.body.addEventListener("click", function (event) {
                    let target = event.target.closest("button, a, input, select, textarea");
                    if (target) {
                        event.preventDefault();
                        window.location.href = "request_access.php"; // Redirect to request form
                    }
                });
            }
        });
    </script>
</body>
</html>