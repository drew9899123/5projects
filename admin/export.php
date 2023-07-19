<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="data.csv"');

include '../connect.php';
$sql = "SELECT
        std_id,
        password,
        prefix,
        CONCAT_WS(' ', name, surname) AS full_name,
        CONCAT('(', SUBSTRING_INDEX(level, '(', -1)) AS level,
        field_name,
        study_group.group_name,
        system,
        tel,
        student.progression_id,
        progression.progression_name,
        progression.color
        FROM student
        INNER JOIN studyfield ON student.field_id = studyfield.field_id
        INNER JOIN study_group ON student.group_id = study_group.group_id
        INNER JOIN progression ON student.progression_id = progression.progression_id
        WHERE student.progression_id <> 2";
$result= $con->query($sql);
$row = mysqli_fetch_array($result);

// Create the CSV file
$output = fopen('php://output', 'w');

// Write the BOM (Byte Order Mark) for UTF-8 encoding
fwrite($output, "\xEF\xBB\xBF");

// Write the column headers and encode them in UTF-8
fputcsv($output, array(
    'รหัสนักศึกษา',
    'คำนำหน้า',
    'ชื่อ-สกุล',
    'ชื่อกลุ่ม',
    'ระบบ',
    'สถานะ'
), ',', '"');

// Loop through the data, encode each value in UTF-8, and write each row to the CSV file
while ($row = mysqli_fetch_array($result)) {
    $csvRow = array(
        $row['std_id'],
        $row['prefix'],
        $row['full_name'],
        $row['group_name'],
        $row['system'],
        $row['progression_name']
        // Add more columns as needed
    );
    
    // Encode each value in UTF-8
    array_walk($csvRow, function(&$value, $key) {
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    });

    fputcsv($output, $csvRow, ',', '"');
}

// Close the output file
fclose($output);

// End the script execution
exit();
?>
