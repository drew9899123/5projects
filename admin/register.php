<?php
include '../connect.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $psw = $_POST['password'];
    $psw_cf = $_POST['password_confirm'];

    $sql = "SELECT * from admin where username = '$username'";
    $result = $con->query($sql);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        echo "<script>alert('มีชื่อผู้ใช้ " . $username . " อยู่ในระบบอยู่แล้ว')</script>";
    } else {
        if ($psw != $psw_cf) {
            echo "<script>alert('โปรดยืนยันรหัสผ่านให้ถูกต้อง')</script>";
        } else {
            $sql_insert = "INSERT INTO admin VALUES (null,'$fullname','$username','$psw','ผู้ดูแลระบบทั่วไป','waiting')";
            $result_insert = $con->query($sql_insert);
            if (!$result_insert) {
                echo "<script>alert('เกิดปัญหาในการสมัคร')</script>";
            } else {
                echo "<script>alert('สมัครเป็นเจ้าหน้าที่แล้ว โปรดรอการยืนยัน')</script>";
                echo "<script>window.location.href='login.php'</script>";
            }
        }
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
    <title>สมัครเป็นเจ้าหน้าที่ดูแลระบบ</title>
</head>

<body>
    <?php include '../style/style.php'; ?>

    <div class="container-sm my-5 d-flex flex-column align-items-center">

        <div class="acadamy text-center">
            <img src="../img/veclogo.png" alt="" width="100" class="mb-2">
            <p>วิทยาลัยเทคนิคสัตหีบ <br> Thai-Austrian Technical College</p>
        </div>

        <div class="title">
            <h3>ระบบจัดการข้อมูลการฝึกงานและติดตามนักศึกษา</h3>
        </div>

        <div class="card" style="max-width:600px; width: 100%;">
            <div class="card-header">
                <b>สมัครเป็นเจ้าหน้าที่ดูแลระบบ</b>
            </div>
            <div class="card-body">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="return validateForm()">
                    <div class="control mb-3">
                        <div class="form-label">ชื่อผู้ใช้</div>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="control mb-3">
                        <div class="form-label">ชื่อ-นามสกุล</div>
                        <input type="text" name="fullname" class="form-control">
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
                    <div class="col-sm-12 mb-3">
                        <div class="form-label">ยืนยันรหัสผ่าน</div>
                        <div class="input-group gap-2">
                            <input type="password" name="password_confirm" id="passwordConfirmInput" class="form-control rounded" required>
                            <div class="input-group-append">
                                <button class="btn " type="button" id="showPasswordConfirmButton">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>
                        <div id="passwordMatchAlert" class="alert alert-danger d-none mt-3" role="alert">
                            โปรดยืนยันรหัสผ่านให้ถูกต้อง
                        </div>
                    </div>
                    <div class="control mb-3">
                        <input type="submit" class="btn btn-success" value="สมัคร" name="submit">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <a href="login.php">มีบัญชีอยู่แล้ว เข้าสู่ระบบ</a>
            </div>
        </div>
        <div class="d-flex gap-3 mt-3">
            <a href="../login.php">เข้าสู่ระบบนักศึกษา</a>
            <a href="#">ช่วยเหลือ</a>
        </div>

    </div>
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const passwordConfirmInput = document.getElementById('passwordConfirmInput');
        const showPasswordButton = document.getElementById('showPasswordButton');
        const showPasswordConfirmButton = document.getElementById('showPasswordConfirmButton');
        const passwordMatchAlert = document.getElementById('passwordMatchAlert');

        showPasswordButton.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showPasswordButton.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            } else {
                passwordInput.type = 'password';
                showPasswordButton.innerHTML = '<i class="bi bi-eye-fill"></i>';
            }
        });

        showPasswordConfirmButton.addEventListener('click', function() {
            if (passwordConfirmInput.type === 'password') {
                passwordConfirmInput.type = 'text';
                showPasswordConfirmButton.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            } else {
                passwordConfirmInput.type = 'password';
                showPasswordConfirmButton.innerHTML = '<i class="bi bi-eye-fill"></i>';
            }
        });

        function validateForm() {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordMatchAlert.classList.remove('d-none');
                return false;
            } else {
                passwordMatchAlert.classList.add('d-none');
                return true;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>