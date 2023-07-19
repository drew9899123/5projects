<?php
include '../connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $psw = $_POST['password'];

    $sql = "SELECT *
                    FROM
                        admin
                    WHERE
                        username = '$username' AND password = '$psw';
                    ";
    $result = $con->query($sql);
    $row = mysqli_fetch_array($result);
    if ($row == 0) {
        echo "<script>alert('ไม่พบผู้ใช้ $username')</script>";
    } elseif ($row['status']=='waiting'){ 
        echo "<script>alert('ผู้ใช้ $username อยู่ในระหว่างรออนุมัติ')</script>";
    } elseif ($row['status']=='denied'){ 
        echo "<script>alert('ผู้ใช้ $username ไม่ผ่านการอนุมัติ')</script>";
    } else {
        $_SESSION['username'] = $row['username'];
        header('location:index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../img/veclogo.png"/>
    <title>เข้าสู่ระบบ</title>
</head>

<body>
    <?php include '../style/style.php'; ?>

    <div class="container-sm d-flex flex-column align-items-center">

        <div class="acadamy mt-5 text-center">
            <img src="../img/veclogo.png" alt="" width="100" class="mb-2">
            <p>วิทยาลัยเทคนิคสัตหีบ <br> Thai-Austrian Technical College</p>
        </div>

        <div class="title">
            <h3>ระบบจัดการข้อมูลการฝึกงานและติดตามนักศึกษา</h3>
        </div>

        <div class="card" style="max-width:600px; width: 100%;">
            <div class="card-header">
                <b>เข้าสู่ระบบเจ้าหน้าที่</b>
            </div>
            <div class="card-body">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="control mb-3">
                        <div class="form-label">ชื่อผู้ใช้</div>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="form-label">รหัสผ่าน</div>
                        <div class="input-group gap-2">
                            <input type="password" name="password" id="passwordInput" class="form-control rounded" required>
                            <div class="input-group-append">
                                <button class="btn " type="button" id="showPasswordButton">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="control mb-3">
                        <input type="submit" class="btn btn-success" value="เข้าสู่ระบบ" name="login">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <a href="register.php">สมัครเป็นเจ้าหน้าที่ดูแลระบบ</a>
            </div>
        </div>
        <div class="d-flex gap-3 mt-3">
            <a href="../login.php">เข้าสู่ระบบนักศึกษา</a>
            <a href="#">ช่วยเหลือ</a>
        </div>

    </div>
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const showPasswordButton = document.getElementById('showPasswordButton');

        showPasswordButton.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showPasswordButton.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            } else {
                passwordInput.type = 'password';
                showPasswordButton.innerHTML = '<i class="bi bi-eye-fill"></i>';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>