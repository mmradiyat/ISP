<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints list</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = $page_name = "Complaints List";

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
            $_SESSION['customer_id_details'] = $_POST['c_id'];
            echo "<script> window.location.href='customer_details.php';</script>";
            die();
        }

        // connect to the database
        require '_database_connect.php';

        // Mark complaint as replied
        if(isset($_POST['mark_replied'])){
            $complaint_id = mysqli_real_escape_string($connect, $_POST['complaint_id']);
            
            // Update complaint state to 'Replied'
            $update_sql = "UPDATE `complaint` SET `state` = 'Replied' WHERE `id` = '$complaint_id'";
            
            if(mysqli_query($connect, $update_sql)){
                echo "<script>
                    alert('Complaint marked as replied successfully!');
                    window.location.href = window.location.href.split('?')[0];
                </script>";
            } else {
                echo "<script>
                    alert('Error updating complaint: " . mysqli_error($connect) . "');
                    window.location.href = window.location.href.split('?')[0];
                </script>";
            }
            
            // Prevent form resubmission
            echo "<script>history.pushState({}, '', '');</script>";
            die();
        }

        //Searchbar function
        $find_complaints_sql = "";
        $word = mysqli_real_escape_string($connect, $word);
        if(isset($key) && isset($word)){
            $find_complaints_sql = "SELECT 
                complaint.id AS com_id,
                complaint.title AS com_title,
                complaint.type AS com_type,
                complaint.state AS com_state,
                DATE_FORMAT(complaint.complaining_date, '%D %M, %Y') AS com_date,
                customer.id AS cus_id
                FROM `complaint`
                JOIN customer ON complaint.customer_id = customer.id ";
            if($key=="all" && $word!=""){
                 $find_complaints_sql .= "WHERE CONCAT(complaint.title, complaint.type, complaint.state, customer.phone, customer.email) LIKE '%$word%'";
            } else if($key=="title" && $word!=""){
                 $find_complaints_sql .= "WHERE complaint.title LIKE '%$word%'";
            } else if($key=="state" && $word!=""){
                $find_complaints_sql .= "WHERE complaint.state LIKE '%$word%'";
            } else if($key=="type" && $word!=""){
                $find_complaints_sql .= "WHERE complaint.type LIKE '%$word%'";
            } else if($key=="cu_phone" && $word!=""){
                $find_complaints_sql .= "WHERE customer.phone LIKE '%$word%'";
            } else if($key=="cu_email" && $word!=""){
            $find_complaints_sql .= "WHERE customer.email LIKE '%$word%'";
            } else {
                $find_complaints_sql .= "WHERE 1";
            }
        }

        //Query
        $find_complaints = mysqli_query($connect, $find_complaints_sql);
        $total_complaints = mysqli_num_rows($find_complaints);
        
        // Close the database connection
        mysqli_close($connect);
        
    ?>

    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="input-group">
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="title">Title</option>
                    <option value="state">Status</option>
                    <option value="type">Type</option>
                    <option value="cu_phone">Customer phone</option>
                    <option value="cu_email">Customer email</option>
                </select>

                <input type="text" class="form-control" name="word" placeholder="Search">

                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>



    <?php
        // connect to the database
        require '_database_connect.php';

        // Showing Customer list
        if($total_complaints>0){
            echo "
            <div class='container overflow-x-scroll mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Result: $total_complaints</h7>
                </div>
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Complaint Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
                for($i=0; $i<$total_complaints; $i++){
                    $com_cu = mysqli_fetch_assoc($find_complaints);

                    // Determine button state based on complaint status
                    $button_html = "";
                    if($com_cu['com_state'] == 'Replied'){
                        $button_html = "<span class='badge bg-success'>Already Replied</span>";
                    } else {
                        $button_html = "
                            <form method='post' style='display: inline;'>
                                <input type='hidden' name='complaint_id' value='{$com_cu['com_id']}'>
                                <button type='submit' name='mark_replied' class='btn btn-success btn-sm' 
                                        onclick='return confirm(\"Are you sure you want to mark this complaint as replied?\")'>
                                    <i class='fa-solid fa-check'></i> Mark as Replied
                                </button>
                            </form>
                        ";
                    }

                    echo"<tr>
                            <td>$com_cu[com_title]</td>
                            <td>$com_cu[com_type]</td>
                            <td>
                                <span class='badge " . ($com_cu['com_state'] == 'Replied' ? 'bg-success' : 'bg-warning') . "'>
                                    $com_cu[com_state]
                                </span>
                            </td>
                            <td>$com_cu[com_date]</td>
                            <td>$button_html</td>
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
