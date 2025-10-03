<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <?php include '_link_common.php'; ?>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="dash_admin.css">
</head>
<body>

<?php
// Page variables
$page_type = "employee";
$page_name = "employee";

$key = "all";
$word = "";

// ===== Assign Task Button =====
if(isset($_POST['assign_task'])) {
    $_SESSION['employee_id_task'] = $_POST['employee_id'];
    echo "<script>window.location.href='assign_task.php';</script>";
    die();
}

// ===== Details Button =====
// if(isset($_POST['details'])) {
//     $_SESSION['employee_id_details'] = $_POST['employee_id'];

//     // Open the details page in a new tab
//     echo "<script>window.open('employee_details_admin.php', '_blank');</script>";

//     // Prevent form resubmission on reload
//     echo "<script>history.replaceState({}, '', window.location.pathname);</script>";
//     die();
// }
if(isset($_POST['details'])) {
    $_SESSION['employee_id_details'] = $_POST['employee_id'];
    echo "<script>window.open('employee_details_admin.php', '_blank');</script>";
    exit();
}

// ===== Handle Search & Reset =====
if(isset($_GET['search'])){
    $key = $_GET['key'];
    $word = $_GET['word'];
}

if(isset($_GET['reset'])){
    $key = "all";
    $word = "";
}

// ===== Navbar =====
require '_nav_admin.php';

// ===== Fetch Employees =====
require '_database_connect.php';

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
    } else {
        $show_employee_sql = "SELECT * FROM `employee`";
    }
} else {
    $show_employee_sql = "SELECT * FROM `employee`";
}

$run_show_employee = mysqli_query($connect, $show_employee_sql);
$total_employee = mysqli_num_rows($run_show_employee);
?>

<!-- Search Bar -->
<div class="container my-2">
    <form method="get">
        <div class="input-group">
            <a href="employee_recruit.php" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Recruit
            </a>

            <select class="search-select" name="key" style="width: 20%;">
                <option value="all" <?= $key=="all"?"selected":"" ?>>Search By</option>
                <option value="name" <?= $key=="name"?"selected":"" ?>>Name</option>
                <option value="post" <?= $key=="post"?"selected":"" ?>>Post</option>
                <option value="phone" <?= $key=="phone"?"selected":"" ?>>Phone</option>
                <option value="email" <?= $key=="email"?"selected":"" ?>>Email</option>
                <option value="e-id" <?= $key=="e-id"?"selected":"" ?>>Employee-ID</option>
            </select>
            <input type="text" class="form-control" name="word" placeholder="Search" value="<?= htmlspecialchars($word) ?>">
            <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i> Re-set</button>
        </div>
    </form>
</div>

<!-- Employee Table -->
 <!-- Employee Table -->
<!-- Employee Table -->
<div class='container overflow-auto mt-4'>
<?php
if($total_employee > 0){
    echo "
        <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Post</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Completed Task</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    ";

    while($employee = mysqli_fetch_assoc($run_show_employee)){
        // Skip self or equal role (optional)
        if($employee['is_sup_admin'] && $_SESSION['user'] == "sup_admin") continue;
        if(($employee['is_admin'] || $employee['is_sup_admin']) && $_SESSION['user'] == "admin") continue;

        // Get task count
        $task_query = mysqli_query($connect, "SELECT COUNT(*) as total FROM `task` WHERE `employee_id`='{$employee['id']}'");
        $task_row = mysqli_fetch_assoc($task_query);
        $task_number = $task_row['total'];
        ?>

        <tr>
            <td>
    <img src="<?= htmlspecialchars($employee['photo_file']) ?>" 
         class="rounded-circle" 
         style="width:50px; height:50px; object-fit:cover;" 
         alt="Employee Photo">
</td>

            
            <td><?= htmlspecialchars($employee['name']) ?></td>
            <td><?= htmlspecialchars($employee['post']) ?></td>
            <td><?= htmlspecialchars($employee['phone']) ?></td>
            <td><?= htmlspecialchars($employee['email']) ?></td>
            <td><?= $task_number ?></td>
            <td>
                <!-- Assign Task Button (POST) -->
                <form method="post" style="display:inline-block">
                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                    <button class="btn btn-danger mb-1" type="submit" name="assign_task" style="width:114px">Assign Task</button>
                </form>

                <!-- Details Button (GET, opens new tab) -->
                <a href="employee_details_admin.php?id=<?= $employee['id'] ?>"  class="btn btn-success" style="width:114px">Details</a>
            </td>
        </tr>

    <?php
    }

    echo "
            </tbody>
        </table>
    ";
} else {
    echo "<p class='text-center'>No employees found.</p>";
}
?>
</div>


<!-- Employee Table -->

</body>
</html>
