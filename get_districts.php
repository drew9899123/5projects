<?php
include 'connect.php';

$provinceId = $_POST['provinceId'];

// Query the database to fetch the districts based on the selected province
$sql_district = "SELECT * FROM district WHERE pro_id = $provinceId";
$result_districts = $con->query($sql_district);

// Generate the HTML options for districts
$options = '<option value="">เลือก...</option>';
while ($row_district = mysqli_fetch_array($result_districts)) {
    $options .= '<option value="' . $row_district['dis_id'] . '">' . $row_district['name_th'] . '</option>';
}

echo $options;
