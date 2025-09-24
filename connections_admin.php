<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>connections list</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = "connections";
        $page_name = "cunnections";

        //variables
        $key = "all";
        $word = "";

        //Navbar
        require '_nav_admin.php';

        //if searched
        if(isset($_GET['search'])){
            $key = $_GET['key'];
            $word = $_GET['word'];
        }

        //If Re-Set
        if(isset($_GET['reset'])){
            $key = "all";
            $word = "";
        }

        //Seeing Details
        if(isset($_POST['details'])){
            $_SESSION['connections_id_details'] = $_POST['con_id'];
            echo "<script> window.location.href='connections_details_admin.php';</script>";
            die();
        }
        
    ?>



    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="input-group">
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="name">Name</option>
                    <option value="address">Address</option>
                    <option value="type">Type (res/org)</option>
                    <option value="speed">Speed</option>
                    <option value="price">Price</option>
                </select>
                <input type="text" class="form-control" name="word" placeholder="Search">
                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>



    <?php
        // connect to the database
        require '_database_connect.php';

        //Sarcbar function
        $find_connections_sql = "SELECT * FROM `connections`";
        if(isset($key) && isset($word)){
            if($key=="all" && $word!=""){
                $find_connections_sql = "SELECT * FROM `connections` WHERE CONCAT(name, address) LIKE '%$word%'";
            } else if($key=="name" && $word!=""){
                $find_connections_sql = "SELECT * FROM `connections` WHERE `name` LIKE '%$word%'";
            } else if($key=="address" && $word!=""){
                $find_connections_sql = "SELECT * FROM `connections` WHERE `address` LIKE '%$word%'";
            } else if($key=="type" && $word!=""){
                $find_connections_sql = "SELECT * FROM `connections` WHERE `type` LIKE '%$word%'";
            }
        }

        //Query
        $find_connections = mysqli_query($connect, $find_connections_sql);
        $total_connections = mysqli_num_rows($find_connections);

        // Showing connections list
        if($total_connections>0){
            echo "
            <div class='container overflow-auto mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Result: $total_connections</h7>
                </div>
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>State</th>
                            <th>Speed (Mbps)</th>
                            <th>Price (TK)</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>";
                for($i=0; $i<$total_connections; $i++){
                    // Get row
                    $connections = mysqli_fetch_assoc($find_connections);

                    //Get plan details
                    $plan_sql = "SELECT * FROM `plans` WHERE `type` = '{$connections['type']}' AND `id` = '{$connections['plan_id']}'";
                    $get_plan = mysqli_query($connect, $plan_sql);
                    $plan = mysqli_fetch_assoc($get_plan);

                    //To show Update State Not Requested Plan ID
                    if (strpbrk($connections['state'], "0123456789")!=false){$connections['state']="Update request pending";}


                    //Search Filter for Speed and Price
                    if($key=="speed" && $word!=""){if ($plan['speed']!=$word){continue;}}
                    if($key=="price" && $word!=""){if ($plan['price']!=$word){continue;}}

                    //Get Plan type
                    if($connections['type'] == "residential_plans"){
                        $type = "Residential Plans";
                    } else if($connections['type'] == "organizational_plans"){
                        $type = "Organizational Plans";
                    }

                    echo"<tr>
                            <td>$connections[name]</td>
                            <td>$connections[address]</td>
                            <td>$type</td>
                            <td>$connections[state]</td>
                            <td>$plan[speed]</td>
                            <td>$plan[price]</td>
                            <td>
                                <form method='post'>
                                    <input class='visually-hidden' type='text' name='con_id' value='$connections[id]'</input>
                                    <button class='btn btn-success' type='submit' name='details'>Details</button>
                                </form>
                            </td>
                        </tr>";
                }
                echo"
                    </tbody>
                </table>
            </div>";;
        }
        
        // Close the database connection
        mysqli_close($connect);
    
    ?>


</body>
</html>
