<?php 
session_start(); 

// Handle export functionality FIRST - before any HTML output
if(isset($_GET['export'])) {
    // Connect to the database
    require '_database_connect.php';
    
    // Get filter parameters
    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : "all";
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";
    $key = isset($_GET['key']) ? $_GET['key'] : "all";
    $word = isset($_GET['word']) ? $_GET['word'] : "";
    
    // Build the same query as in the main page
    $payment_sql = "SELECT 
        b.id AS bill_id,
        b.amount,
        b.state,
        b.due_date,
        b.payment_id,
        c.name AS connection_name,
        c.address AS connection_address,
        c.type AS connection_type,
        c.state AS connection_state,
        cus.name AS customer_name,
        cus.phone AS customer_phone,
        cus.email AS customer_email,
        p.pay_date,
        p.tran_id,
        p.card_type,
        p.tran_status
    FROM bill b
    LEFT JOIN connections c ON b.connection_id = c.id
    LEFT JOIN customer cus ON c.customer_id = cus.id
    LEFT JOIN payments p ON b.payment_id = p.id
    WHERE 1=1";

    // Add filters (same logic as main page)
    if ($status_filter != "all") {
        $payment_sql .= " AND b.state = '$status_filter'";
    }

    if (!empty($date_from) && !empty($date_to)) {
        $payment_sql .= " AND b.due_date BETWEEN '$date_from' AND '$date_to'";
    } elseif (!empty($date_from)) {
        $payment_sql .= " AND b.due_date >= '$date_from'";
    } elseif (!empty($date_to)) {
        $payment_sql .= " AND b.due_date <= '$date_to'";
    }

    if (!empty($word)) {
        switch ($key) {
            case "customer_name":
                $payment_sql .= " AND cus.name LIKE '%$word%'";
                break;
            case "connection_name":
                $payment_sql .= " AND c.name LIKE '%$word%'";
                break;
            case "amount":
                $payment_sql .= " AND b.amount LIKE '%$word%'";
                break;
            case "all":
            default:
                $payment_sql .= " AND (cus.name LIKE '%$word%' OR c.name LIKE '%$word%' OR b.amount LIKE '%$word%' OR cus.phone LIKE '%$word%')";
                break;
        }
    }

    $payment_sql .= " ORDER BY b.due_date DESC, b.id DESC";
    
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="payment_report_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, array('Bill ID', 'Customer Name', 'Customer Phone', 'Connection Name', 'Connection Address', 'Amount', 'Status', 'Due Date', 'Payment Date', 'Payment Method', 'Transaction ID'));
    
    // Execute query and export data
    $export_result = mysqli_query($connect, $payment_sql);
    while($row = mysqli_fetch_assoc($export_result)) {
        fputcsv($output, array(
            $row['bill_id'],
            $row['customer_name'],
            $row['customer_phone'],
            $row['connection_name'],
            $row['connection_address'],
            $row['amount'],
            $row['state'],
            $row['due_date'],
            !empty($row['pay_date']) ? date('Y-m-d', strtotime($row['pay_date'])) : '',
            $row['card_type'],
            $row['tran_id']
        ));
    }
    
    fclose($output);
    mysqli_close($connect);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = "reports";
        $page_name = "Payment Reports";

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

        // Filter by payment status
        if(isset($_GET['status_filter'])){
            $status_filter = $_GET['status_filter'];
        } else {
            $status_filter = "all";
        }

        // Date range filter
        if(isset($_GET['date_from']) && isset($_GET['date_to'])){
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
        } else {
            $date_from = "";
            $date_to = "";
        }
        
    ?>

    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" name="status_filter">
                        <option value="all" <?php echo ($status_filter == "all") ? "selected" : ""; ?>>All Status</option>
                        <option value="Paid" <?php echo ($status_filter == "Paid") ? "selected" : ""; ?>>Paid</option>
                        <option value="Unpaid" <?php echo ($status_filter == "Unpaid") ? "selected" : ""; ?>>Unpaid</option>
                        <option value="Late" <?php echo ($status_filter == "Late") ? "selected" : ""; ?>>Late</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_from" value="<?php echo $date_from; ?>" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_to" value="<?php echo $date_to; ?>" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="key">
                        <option value="all" <?php echo (isset($key) && $key=="all") ? "selected" : ""; ?>>Search By</option>
                        <option value="customer_name" <?php echo (isset($key) && $key=="customer_name") ? "selected" : ""; ?>>Customer Name</option>
                        <option value="connection_name" <?php echo (isset($key) && $key=="connection_name") ? "selected" : ""; ?>>Connection Name</option>
                        <option value="amount" <?php echo (isset($key) && $key=="amount") ? "selected" : ""; ?>>Amount</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="word" placeholder="Search" value="<?php echo isset($word) ? $word : ""; ?>">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger btn-outline-light" name="search" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <button class="btn btn-success btn-outline-light" name="reset" type="submit">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </button>
                    <button class="btn btn-info btn-outline-light" name="export" type="submit">
                        <i class="fa-solid fa-download"></i> Export
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php
        // Connect to the database
        require '_database_connect.php';

        // Build the query
        $payment_sql = "SELECT 
            b.id AS bill_id,
            b.amount,
            b.state,
            b.due_date,
            b.payment_id,
            c.name AS connection_name,
            c.address AS connection_address,
            c.type AS connection_type,
            c.state AS connection_state,
            cus.name AS customer_name,
            cus.phone AS customer_phone,
            cus.email AS customer_email,
            p.pay_date,
            p.tran_id,
            p.card_type,
            p.tran_status
        FROM bill b
        LEFT JOIN connections c ON b.connection_id = c.id
        LEFT JOIN customer cus ON c.customer_id = cus.id
        LEFT JOIN payments p ON b.payment_id = p.id
        WHERE 1=1";

        // Add status filter
        if ($status_filter != "all") {
            $payment_sql .= " AND b.state = '$status_filter'";
        }

        // Add date range filter
        if (!empty($date_from) && !empty($date_to)) {
            $payment_sql .= " AND b.due_date BETWEEN '$date_from' AND '$date_to'";
        } elseif (!empty($date_from)) {
            $payment_sql .= " AND b.due_date >= '$date_from'";
        } elseif (!empty($date_to)) {
            $payment_sql .= " AND b.due_date <= '$date_to'";
        }

        // Add search filter
        if (isset($key) && isset($word) && !empty($word)) {
            switch ($key) {
                case "customer_name":
                    $payment_sql .= " AND cus.name LIKE '%$word%'";
                    break;
                case "connection_name":
                    $payment_sql .= " AND c.name LIKE '%$word%'";
                    break;
                case "amount":
                    $payment_sql .= " AND b.amount LIKE '%$word%'";
                    break;
                case "all":
                default:
                    $payment_sql .= " AND (cus.name LIKE '%$word%' OR c.name LIKE '%$word%' OR b.amount LIKE '%$word%' OR cus.phone LIKE '%$word%')";
                    break;
            }
        }

        $payment_sql .= " ORDER BY b.due_date DESC, b.id DESC";

        // Execute query
        $payment_result = mysqli_query($connect, $payment_sql);
        $total_payments = mysqli_num_rows($payment_result);

        // Get summary statistics
        $stats_sql = "SELECT 
            COUNT(*) as total_bills,
            SUM(CASE WHEN state = 'Paid' THEN 1 ELSE 0 END) as paid_bills,
            SUM(CASE WHEN state = 'Unpaid' THEN 1 ELSE 0 END) as unpaid_bills,
            SUM(CASE WHEN state = 'Late' THEN 1 ELSE 0 END) as late_bills,
            SUM(amount) as total_amount,
            SUM(CASE WHEN state = 'Paid' THEN amount ELSE 0 END) as paid_amount,
            SUM(CASE WHEN state = 'Unpaid' THEN amount ELSE 0 END) as unpaid_amount,
            SUM(CASE WHEN state = 'Late' THEN amount ELSE 0 END) as late_amount
        FROM bill";
        
        $stats_result = mysqli_query($connect, $stats_sql);
        $stats = mysqli_fetch_assoc($stats_result);

        // Display summary statistics
        echo "
        <div class='container mt-4'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='card text-bg-success'>
                        <div class='card-body text-center'>
                            <h5><i class='fa-solid fa-check-circle'></i> Paid Bills</h5>
                            <h3>$stats[paid_bills]</h3>
                            <p>Amount: ৳" . number_format($stats['paid_amount']) . "</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card text-bg-warning'>
                        <div class='card-body text-center'>
                            <h5><i class='fa-solid fa-clock'></i> Unpaid Bills</h5>
                            <h3>$stats[unpaid_bills]</h3>
                            <p>Amount: ৳" . number_format($stats['unpaid_amount']) . "</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card text-bg-danger'>
                        <div class='card-body text-center'>
                            <h5><i class='fa-solid fa-exclamation-triangle'></i> Late Bills</h5>
                            <h3>$stats[late_bills]</h3>
                            <p>Amount: ৳" . number_format($stats['late_amount']) . "</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card text-bg-info'>
                        <div class='card-body text-center'>
                            <h5><i class='fa-solid fa-chart-line'></i> Total Revenue</h5>
                            <h3>$stats[total_bills]</h3>
                            <p>Amount: ৳" . number_format($stats['total_amount']) . "</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>";

        // Display payment list
        if($total_payments > 0){
            echo "
            <div class='container overflow-x-scroll mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Results: $total_payments</h7>
                </div>
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Bill ID</th>
                            <th>Customer</th>
                            <th>Connection</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Payment Date</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody>";
            
            while($payment = mysqli_fetch_assoc($payment_result)){
                $status_badge = "";
                switch($payment['state']) {
                    case 'Paid':
                        $status_badge = "<span class='badge bg-success'>Paid</span>";
                        break;
                    case 'Unpaid':
                        $status_badge = "<span class='badge bg-warning'>Unpaid</span>";
                        break;
                    case 'Late':
                        $status_badge = "<span class='badge bg-danger'>Late</span>";
                        break;
                    default:
                        $status_badge = "<span class='badge bg-secondary'>$payment[state]</span>";
                }

                $payment_date = !empty($payment['pay_date']) ? date('Y-m-d', strtotime($payment['pay_date'])) : '-';
                $card_type = !empty($payment['card_type']) ? $payment['card_type'] : '-';
                $tran_id = !empty($payment['tran_id']) ? $payment['tran_id'] : '-';

                echo "<tr>
                        <td>$payment[bill_id]</td>
                        <td>
                            <strong>$payment[customer_name]</strong><br>
                            <small class='text-muted'>$payment[customer_phone]</small>
                        </td>
                        <td>
                            <strong>$payment[connection_name]</strong><br>
                            <small class='text-muted'>$payment[connection_address]</small>
                        </td>
                        <td><strong>৳" . number_format($payment['amount']) . "</strong></td>
                        <td>$status_badge</td>
                        <td>$payment[due_date]</td>
                        <td>$payment_date</td>
                        <td>$card_type</td>
                        <td><small>$tran_id</small></td>
                    </tr>";
            }
            
            echo "
                    </tbody>
                </table>
            </div>";
        } else {
            echo "
            <div class='container mt-4'>
                <div class='alert alert-info text-center'>
                    <i class='fa-solid fa-info-circle'></i>
                    <h4>No Payment Records Found</h4>
                    <p>No payment records match your search criteria.</p>
                </div>
            </div>";
        }

        
        // Close the database connection
        mysqli_close($connect);
    
    ?>

</body>
</html>
