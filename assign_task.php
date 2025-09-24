<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Assign</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="assign_task.css">
    <!-- Link End -->

</head>
<body>
<?php
    //Defining Page
    $page_type = "manage_employee";
    $page_name = "Assign Task";

    //Variable
    $key = "all";
    $word = "";

    // If choose button clicked
    if(isset($_POST['choose-em'])) {
        $_SESSION['employee_id_task'] = $_POST['employee_id'];
    }

    // If detail button clicked
    if(isset($_POST['details'])) {
        // Connect to the database
        require '_database_connect.php';
        // Close the database connection
        mysqli_close($connect);

        // Season Variable for employee details
        $_SESSION['employee_id_details'] = $_POST['employee_id'];

        // Generate the JavaScript code to open the new tab
        echo "<script>
            var newTab = window.open('employee_details_admin.php', '_blank');
            newTab.onload = function() {
                window.location.href = window.location.href.split('?')[0];
            };
        </script>";

        // Prevent form resubmission on reload
        echo "<script>history.pushState({}, '', '');</script>";
    }
    
    //Get Connection info
    if (isset($_SESSION['connections_id_details'])){
        // Get Connection Info
        // connect to the database
        require '_database_connect.php';
        $find_connection_sql = "SELECT * FROM `connections` WHERE `id` = '{$_SESSION['connections_id_details']}'";
        $find_connection = mysqli_query($connect, $find_connection_sql);
        $connection = mysqli_fetch_assoc($find_connection);
        // Close the database connection
        mysqli_close($connect);
    }

    // If Assigned button clicked
    if(isset($_POST['assign'])) {
        if (isset($_SESSION['employee_id_task'])){
            $name = $_POST['task_name'];
            $em_id = $_SESSION['employee_id_task'];
            $ref = $_SESSION['connections_id_details'];
            $details = $_POST['task_details'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $address = $_POST['task_address'];

            //insert task
            $task_insertion_sql = "INSERT INTO `task` (`name`, `state`, `address`, `employee_id`, `task_ref`, `details`, `start`, `end`) VALUES ('$name', 'Pending', '$address', '$em_id', '$ref', '$details', '$start_date', '$end_date');";
            // connect to the database
            require '_database_connect.php';
            $run_task_insertion = mysqli_query($connect, $task_insertion_sql);
            // Close the database connection
            mysqli_close($connect);

            if($run_task_insertion){
                unset($_SESSION['employee_id_task']);


                //Set a IN process state in connection state and redirect to the previous page
                if(isset($_SESSION['connections_id_details'])){
                    //Set the connection state based on previous state
                    if($connection['state']=="Pending") $new_con_state = "Connection in process";
                    elseif($connection['state']=="Update pending")  $new_con_state = "Update in process";
                    elseif($connection['state']=="Disconnection pending")  $new_con_state = "Disconnection in process";
                    else $new_con_state = "Active";

                    //Insert state
                    $update_connection_state_sql = "UPDATE `connections` SET `state` = '{$new_con_state}' WHERE `id` = '{$_SESSION['connections_id_details']}'";
                    // connect to the database
                    require '_database_connect.php';
                    $update_connection_state = mysqli_query($connect, $update_connection_state_sql);
                    // Close the database connection
                    mysqli_close($connect);

                    unset($_SESSION['connections_id_details']);
                    echo "<script> window.location.href='requests.php';</script>";
                }
                else echo "<script> window.location.href='task_reports.php';</script>";
                die();
            }
        } else{
            $no_employee = true;
        }
        

    }

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

    //sql
    if(isset($key) && isset($word)){
        if($key=="all" && $word==""){
            $show_employee_sql = "SELECT * FROM `employee`";
        } else if($key=="all" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE CONCAT(name, post, phone, email, id) LIKE '%$word%'";
        } else if($key=="name" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `name` LIKE '%$word%'";
        } else if($key=="post" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `post` LIKE '%$word%'";
        } else if($key=="phone" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `phone` LIKE '%$word%'";
        } else if($key=="email" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `email` LIKE '%$word%'";
        } else if($key=="e-id" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `id` LIKE '%$word%'";
        } else {$show_employee_sql = "SELECT * FROM `employee`";}
    }

    // connect to the database
    require '_database_connect.php';

    //Query
    $run_show_employee = mysqli_query($connect, $show_employee_sql);
    $total_employee = mysqli_num_rows($run_show_employee);

    // Close the database connection
    mysqli_close($connect);
    
    //Navbar
    require '_nav_admin.php';
?>

<div class="container assign_task_body rounded my-3 py-2">
    <form method="post" class="text-light">

        <!-- Assigned employee -->
        <div class="mb-3">
            <label for="task_details" class="form-label">Assigned Employee</label><br>
            <?php
                if(!isset($_SESSION['employee_id_task'])){
                    echo "
                    <button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#employee_list'>
                        Choose an employee
                    </button>";
                    if(isset($no_employee)){
                        echo "
                    <br><p>Must need an employee</p>";
                    }
                } else {
                    // connect to the database
                    require '_database_connect.php';

                    //get chosen employee info
                    $chosen_employee_sql = "SELECT * FROM `employee` WHERE `id` = '$_SESSION[employee_id_task]'";
                    $run_chosen_employee = mysqli_query($connect, $chosen_employee_sql);
                    $chosen_employee = mysqli_fetch_assoc($run_chosen_employee);

                    //get the pending task
                    $pending_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$chosen_employee['id']}' AND `state` = 'pending'";
                    $find_task = mysqli_query($connect, $pending_task_sql);
                    $pending_task_num_ch = mysqli_num_rows($find_task);
                    
                    //get the late task
                    $late_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$chosen_employee['id']}' AND `state` = 'late'";
                    $find_task = mysqli_query($connect, $late_task_sql);
                    $late_task_num_ch = mysqli_num_rows($find_task);

                    echo "<div class='container bg-light rounded text-black p-2'>
                        <div class='row'>
                            <div class='col align-self-start'>
                                <img src='$chosen_employee[photo_file]' class='rounded-circle' alt='Employee Photo' style='width: 50px'>
                            </div>
                            <div class='col align-self-center'>
                                <b>Name:</b> $chosen_employee[name]
                            </div>
                            <div class='col align-self-center'>
                                <b>Post:</b> $chosen_employee[post]
                            </div>
                            <div class='col align-self-center'>
                            <b>Phone:</b> $chosen_employee[phone]
                            </div>
                            <div class='col align-self-center'>
                                <b>Pending task:</b> $pending_task_num_ch
                            </div>
                            <div class='col align-self-center'>
                                <b>Late task:</b> $late_task_num_ch
                            </div>
                            <div class='col align-self-center'>
                            <button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#employee_list'>Change</button>
                            </div>
                        </div>
                    </div>";
                }
            ?> 
        </div>

        <!-- Task title -->
        <div class="mb-3">
            <label for="task_name" class="form-label">Task Title</label>
            <input required type="text" class="form-control" id="task_name" name="task_name" <?php
            if(isset($_SESSION['connections_id_details'])){
                echo "value='";
                if($connection['state']=="Pending"){
                    echo "New connection in ";
                }else if ($connection['state']=="Update pending") {
                    echo "Update connection in ";
                } else if($connection['state']=="Disconnection pending"){
                    echo "Disconnect connection in ";
                }
                echo"$connection[address]"."'";
            } else {
                echo "placeholder='Enter the title Here'";
            }?>>
        </div>

        <!-- Task Address -->
        <div class="mb-3">
            <label for="task_address" class="form-label">Task Address</label>
            <input required type="text" class="form-control" id="task_address" name="task_address" <?php if(isset($_SESSION['connections_id_details'])){echo "value='"."$connection[address]"."'";} else {
                echo "placeholder='Enter the address Here'";
            }?>>
        </div>

        <!-- Task Start -->
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input required type="date" class="form-control" id="start_date" name="start_date" <?php
            if(isset($_SESSION['connections_id_details'])){
                echo "value='" . date('Y-m-d') . "'";
            }?>>
        </div>

        <!-- Task End -->
        <div class="mb-3">
            <label for="end_date" class="form-label">Last Date</label>
            <input required type="date" class="form-control" id="end_date" name="end_date" <?php
            if(isset($_SESSION['connections_id_details'])){
                echo "value='";
                if($connection['state']=="Pending"){
                    echo "$connection[starting_date]"."'";
                }else if ($connection['state']=="Update pending" || $connection['state']=="Disconnection pending") {
                    echo "$connection[submission_date]"."'";
                }
            }?>>
        </div>

        <!-- task details -->
        <div class="mb-3">
            <label for="task_details" class="form-label">Task Details</label>
            <textarea style="height: 200px;" required type="text" class="form-control" id="task_details" name="task_details" placeholder="Enter the details Here"></textarea>
        </div>
        
        <!-- Assign Button -->
        <button type="submit" class="btn btn-success" name="assign"><i class="fa-solid fa-scroll"></i> Assign</button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="employee_list" data-bs-keyboard="false" aria-labelledby="employee-list" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="employee-list">Choose Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="container my-2">
                    <form method="get">
                        <div class="input-group">  
                            <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button> 
                            <!-- Search bar with filter -->
                            <select class="search-select" name="key" style="width: 20%;">
                                <option value="all" selected>Search By</option>
                                <option value="name">Name</option>
                                <option value="post">Post</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="e-id">Employee-ID</option>
                            </select>
                            <input type="text" class="form-control" name="word" placeholder="Search">
                            <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button> 
                        </div>
                    </form>
                </div>
                <?php
                    // Showing Employee list
                    echo "
                        <div class='container overflow-auto mt-4'>";
                    if($total_employee>0){
                        //Show the Employee List
                        echo "
                            <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>Actions</th>
                                        <th>Phone</th>
                                        <th>Pending Task</th>
                                        <th>Late Task</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ";
                        for($i=0; $i<$total_employee; $i++){
                            // connect to the database
                            require '_database_connect.php';
                            
                            // Get each employee row
                            $employee = mysqli_fetch_assoc($run_show_employee);
                            
                            //skip self and equal positions data
                            if($employee['is_sup_admin'] && $_SESSION['user'] == "sup_admin") continue;
                            elseif(($employee['is_admin'] || $employee['is_sup_admin']) && $_SESSION['user'] == "admin") continue;

                            //get the pending task
                            $pending_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee['id']}' AND `state` = 'pending'";
                            $find_task = mysqli_query($connect, $pending_task_sql);
                            $pending_task_num = mysqli_num_rows($find_task);
                            
                            //get the late task
                            $late_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee['id']}' AND `state` LIKE 'late%'";
                            $find_task = mysqli_query($connect, $late_task_sql);
                            $late_task_num = mysqli_num_rows($find_task);

                            // Close the database connection
                            mysqli_close($connect);
                            
                            echo "
                                    <tr>
                                        <td><img src='$employee[photo_file]' class='rounded-circle' alt='Employee Photo' style='width: 50px'></td>
                                        <td>$employee[name]</td>
                                        <td>$employee[post]</td>
                                        <td>
                                            <form method='post'>
                                                <input class='visually-hidden' type='text' name='employee_id' value='$employee[id]'</input>

                                                <button class='btn btn-danger mb-1' type='submit' name='choose-em' data-bs-dismiss='modal' style='width: 114px'>Choose</button>

                                                <button class='btn btn-success' type='submit' name='details' style='width: 114px'>Details</button>
                                            </form>
                                        </td>
                                        <td>$employee[phone]</td>
                                        <td>$pending_task_num</td>
                                        <td>$late_task_num</td>
                                    </tr>
                            ";
                        }
                        echo "
                                </tbody>
                            </table>
                        ";
                    }
                    echo"</div>";
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Script to open modal by default -->
<!-- <script>
    document.addEventListener("DOMContentLoaded", function(){
        var myModal = new bootstrap.Modal(document.getElementById('employee_list'));
        myModal.show();
    });
</script> -->

</body>
</html>
