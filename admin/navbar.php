<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <!-- Fonts -->
  <title></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- bootstrap 5 icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="icon" type="image/png" href="../img/veclogo.png"/>
  <style>
    * {
      font-family: Prompt;
    }
    table{
      border-radius: 12px;
    }
  </style>
</head>

<?php
include '../connect.php';
if (!isset($_SESSION['username'])) {
  header('location:login.php');
} else {
}
?>

<body>
  <nav class="navbar bg-primary navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../img/veclogo.png" alt="Logo" width="30" style="object-fit: contain;" class="d-inline-block align-text-top">
          วิทยาลัยเทคนิคสัตหีบ
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">หน้าหลัก</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="std_progression.php?keyword=">ตรวจสอบสถานะ</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              เรียกดู
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <!-- <li><a class="dropdown-item" href="std_status.php?keyword=">ตรวจสอบสถานะ</a></li> -->
              <li><a class="dropdown-item" href="std.php?keyword="><i class="bi bi-mortarboard-fill"></i> นักเรียน</a></li>
              <li><a class="dropdown-item" href="std_group.php?keyword="><i class="bi bi-collection-fill"></i> กลุ่มเรียน</a></li>
              <li><a class="dropdown-item" href="department.php?keyword="><i class="bi bi-house-fill"></i> แผนก</a></li>
              <li><a class="dropdown-item disabled" href="supervision.php?keyword="><i class="bi bi-people-fill"></i> ครูนิเทศ</a></li>
              <li><a class="dropdown-item" href="teacher.php?keyword="><i class="bi bi-people-fill"></i> บุคลากร</a></li>
              <li><a class="dropdown-item" href="schedule.php?keyword="><i class="bi bi-calendar-week-fill"></i> กำหนดการ</a></li>
              <li><a class="dropdown-item" href="schedule.php?keyword="><i class="bi bi-building-fill"></i> สถานประกอบการ</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="admin.php?keyword="><i class="bi bi-person-fill-lock"></i> เจ้าหน้าที่ดูแลระบบ</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              ข้อมูล
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <!-- <li><a class="dropdown-item" href="std_status.php?keyword=">ตรวจสอบสถานะ</a></li> -->
              <li><a class="dropdown-item" href="import.php"><i class="bi bi-mortarboard-fill"></i> นำเข้าข้อมูล</a></li>
              <li><a class="dropdown-item" href="std_group.php?keyword="><i class="bi bi-collection-fill"></i> ส่งออกข้อมูล</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <!-- <li><a class="dropdown-item" href="import.php">นำเข้าข้อมูล</a></li> -->
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="setting.php?keyword=">ตั้งค่า</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <ul class="navbar-nav">
            <li class="nav-item">
              <b class="nav-link"><i class="bi bi-person-fill-lock"></i> <?php echo $_SESSION['username']?></b>
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