<?php
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>ตั้งค่า</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container my-5">
        <div class="header">
            <h1>ตั้งค่า</h1>
        </div>
        <?php
            $sql = "SELECT * FROM app_system";
            $result = $con->query($sql);
            while($row = mysqli_fetch_array($result)){
                if($row['activation']==1){
                    $checked = 'checked';
                }
        ?>
            <div class="form-check form-switch pb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" <?php echo $checked?>>
                <label class="form-check-label" for="flexSwitchCheckDefault"><?php echo $row['system_name']?></label>
            </div>
        <?php
            }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>

</html>