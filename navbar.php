<?php
include 'connect.php';
if (!$std_id = $_SESSION['std_id']) {
    header('location:login.php');
}
$std_id = $_SESSION['std_id'];
$sql_user = "SELECT * FROM student WHERE std_id = '$std_id'";
$result_user = $con->query($sql_user);
$row_user = mysqli_fetch_array($result_user);
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- boostrap 5 icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="img/veclogo.png"/>
    <style>
        * {
            font-family: Prompt;
        }
        .navbar{
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }
    </style>
</head>

<body>
    <!-- <nav class="navbar bg-primary navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-between">
            <div>
                <a class="navbar-brand" href="#">
                    <img src="img/สำนักงานคณะกรรมการการอาชีวศึกษา.png" alt="Logo" width="30" class="d-inline-block align-text-top">
                    วิทยาลัยเทคนิคสัตหีบ
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="std_profile.php">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ติดต่อ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">เกี่ยวกับ</a>
                    </li>
                </ul>
            </div>

            <div>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div>
                        <b class="text-light"><i class="bi bi-person-fill"></i> <?php echo $row_user['name'] . ' ' . $row_user['surname'] ?></b>
                    </div>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">ออกจากระบบ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav> -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="">
                <img src="img/veclogo.png" alt="Logo" width="30" class="d-inline-block align-text-top">
                วิทยาลัยเทคนิคสัตหีบ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ติดต่อ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">เกี่ยวกับ</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <b class="nav-link"><i class="bi bi-person-fill"></i> <?php echo $row_user['name'] . ' ' . $row_user['surname'] ?></b>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">ออกจากระบบ</a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>