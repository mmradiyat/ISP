    <!-- Login check  -->
    <?php require '_logincheck_employee.php'; ?>

    
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-xxxl bg-dark sticky-top" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-between">

            <!-- Toggler button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-links" aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-list-ul"></i>
            </button>

            <!-- Page Name -->
            <a class="navbar-brand" href="dash_employee.php"><?php echo $page_name;?></a>

            <!-- Nav Icons -->
            <div class="nav-icon">
                <!-- <a class="navbar-brand px-3" href="employee_notification.php" title="Notification"><i class="fa-regular fa-bell nav-icon"></i></a> -->
                <a class="navbar-brand px-3" href="employee_info.php" title="Profile"><i class="fa-regular fa-user nav-icon"></i></a>
                <a class="navbar-brand px-3" href="logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket nav-icon"></i></a>
            </div>

            <!-- Navbar-links -->
            <div class="collapse navbar-collapse" id="nav-links">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_name == "Home"){echo "op-ac";}?>" aria-current="page" href="dash_employee.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_name == "Pending Tasks"){echo "op-ac";}?>" href="employee_tasks.php?task_type=Pending">Pending Tasks</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_name == "Completed Tasks"){echo "op-ac";}?>" href="employee_tasks.php?task_type=Completed">Completed Tasks</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_name == "Expired Tasks"){echo "op-ac";}?>" href="employee_tasks.php?task_type=Late">Expired Tasks</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <!-- NavBar End -->

