<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .card {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            padding: 2rem;
            border-radius: 15px;
            transition: 0.5s;
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="container my-5" style="height:150px;">
        <div class="card p-3">
            <h3>
                ยินดีต้อนรับสู่ระบบหลังบ้าน คุณ <?php echo $_SESSION['username']; ?>
            </h3>
        </div>
    </div>
</body>

</html>