<?php
session_start();

// Get employee ID from GET
if(isset($_GET['id'])) {
    $employee_id = $_GET['id'];
} else {
    echo "<script>window.location.href='employee.php';</script>";
    exit();
}

// Database connection
require '_database_connect.php';

// Fetch employee info
$find_employee_sql = "SELECT * FROM `employee` WHERE `id` = '{$employee_id}'";
$find_employee = mysqli_query($connect, $find_employee_sql);
$employee = mysqli_fetch_assoc($find_employee);

if(!$employee) {
    echo "<script>alert('Employee not found!'); window.location.href='employee.php';</script>";
    exit();
}

// Task filter/search
$key = isset($_GET['key']) ? $_GET['key'] : "all";
$word = isset($_GET['word']) ? $_GET['word'] : "";

if($key=="all" && $word==""){
    $find_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'";
} else if($key=="all" && $word!=""){
    $find_task_sql = "SELECT * FROM `task` WHERE CONCAT(name, address, state) LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
} else if($key=="title" && $word!=""){
    $find_task_sql = "SELECT * FROM `task` WHERE `name` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
} else if($key=="address" && $word!=""){
    $find_task_sql = "SELECT * FROM `task` WHERE `address` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
} else if($key=="status" && $word!=""){
    $find_task_sql = "SELECT * FROM `task` WHERE `state` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
} else {
    $find_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'";
}

$find_task = mysqli_query($connect, $find_task_sql);
$total_task_to_show = mysqli_num_rows($find_task);

// Task stats
$total_task = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'"));
$completed_task = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}' AND `state` = 'completed'"));
$late_task = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}' AND `state` = 'late'"));
$pending_task = $total_task - $completed_task - $late_task;
$task_ratio = ($total_task > 0) ? round(($completed_task / $total_task) * 100, 2) : 0;

// Page variables
$page_type = $page_name = "Employee Details";

// Navbar
require '_nav_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <?php include '_link_common.php'; ?>
    <link rel="stylesheet" href="customer_details.css">
</head>
<body>

<div class="container">
    <div class="row row-cols-auto bg-light rounded justify-content-center my-4">
        <!-- Profile Picture -->
        <div class="col-auto m-2">
            <img class="pp_img" src="<?= htmlspecialchars($employee['photo_file']) ?>" alt="Profile Picture">
        </div>

        <!-- Employee Details -->
        <div class="col-auto m-2 ms-md-3">
            <div class="bg-dark text-light rounded pb-1 pt-2 px-3 text-center">
                <h2><b><?= htmlspecialchars($employee["name"]) ?></b></h2>
            </div>
            <div class="details fs-5 mt-4">
                <p>
                    <b>Post:</b> <?= htmlspecialchars($employee['post']) ?><br>
                    <b>Address:</b> <?= htmlspecialchars($employee['address']) ?><br>
                    <b>Email:</b> <?= htmlspecialchars($employee['email']) ?><br>
                    <b>Phone:</b> <?= htmlspecialchars($employee['phone']) ?><br>
                    <b>NID:</b> <?= htmlspecialchars($employee['nid']) ?><br>
                    <b>Salary:</b> <?= htmlspecialchars($employee['salary']) ?>
                </p>

                <!-- NID / CV / Certificate Buttons -->
                <div class='btn-group my-1'>
                    <a class='btn btn-info' href='<?= htmlspecialchars($employee['nid_file']) ?>' target='_blank'><i class="fa-solid fa-eye"></i> NID</a>
                    <a class='btn btn-warning' href='<?= htmlspecialchars($employee['nid_file']) ?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                </div>
                <div class='btn-group my-1'>
                    <a class='btn btn-info' href='<?= htmlspecialchars($employee['resume_file']) ?>' target='_blank'><i class="fa-solid fa-eye"></i> CV</a>
                    <a class='btn btn-warning' href='<?= htmlspecialchars($employee['resume_file']) ?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                </div>
                <div class='btn-group my-1'>
                    <a class='btn btn-info' href='<?= htmlspecialchars($employee['certificate_file']) ?>' target='_blank'><i class="fa-solid fa-eye"></i> Certificate</a>
                    <a class='btn btn-warning' href='<?= htmlspecialchars($employee['certificate_file']) ?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Section -->
<div class="container">
    <div>
        <h5 class="p-2 bg-info rounded">Total Assigned Task: <?= $total_task_to_show ?></h5>
    </div>

    <?php if($total_task_to_show > 0): ?>
        <!-- Task Search -->
        <div class='container my-2'>
            <form method="get">
                <input type="hidden" name="id" value="<?= $employee_id ?>">
                <div class='input-group'>
                    <select class='search-select' name='key' style='width:20%;'>
                        <option value='all' <?= $key=='all'?'selected':'' ?>>Search By</option>
                        <option value='title' <?= $key=='title'?'selected':'' ?>>Title</option>
                        <option value='address' <?= $key=='address'?'selected':'' ?>>Address</option>
                        <option value='status' <?= $key=='status'?'selected':'' ?>>Status</option>
                    </select>
                    <input type="text" class="form-control" name="word" value="<?= htmlspecialchars($word) ?>" placeholder="Search">
                    <button class='btn btn-danger btn-outline-light' type="submit">Search</button>
                    <button class='btn btn-success btn-outline-light' type="submit" name="reset">Re-set</button>
                </div>
            </form>
        </div>

        <!-- Task Table -->
        <div class='container overflow-x-scroll'>
            <div class='num_of_res text-light btn btn-dark m-1'>
                <h7 class='pt-2'>Total Result: <?= $total_task_to_show ?></h7>
            </div>
            <table class='table table-info table-striped table-hover m-1 p-2 text-center'>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Last Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($task = mysqli_fetch_assoc($find_task)): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['name']) ?></td>
                            <td><?= htmlspecialchars($task['address']) ?></td>
                            <td><?= htmlspecialchars($task['state']) ?></td>
                            <td><?= htmlspecialchars($task['end']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Task Chart -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-6 col-sm-12 my-3">
            <div class="bg-light rounded p-3" style="width: 100%;">
                <h5 class="text-center"><b>Task Report</b></h5>
                <h6 class="text-center">
                    <b>Total: <?= $total_task ?><br>
                    Success Ratio: <?= $task_ratio ?>%</b>
                </h6>
                <canvas id="doughnut_pi_plans"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Charts JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const d_pai = document.getElementById('doughnut_pi_plans');
new Chart(d_pai, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Late', 'Pending'],
        datasets: [{
            data: [<?= $completed_task ?>, <?= $late_task ?>, <?= $pending_task ?>],
            backgroundColor: ['rgb(0, 255, 0)','rgb(255, 0, 0)','rgb(0, 0, 255)'],
            hoverOffset: 15
        }]
    }
});
</script>

<?php mysqli_close($connect); ?>
</body>
</html>
