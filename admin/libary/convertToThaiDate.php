<?php
function convertToThaiDate($sqlDate)
{
    $date = new DateTime($sqlDate);
    $thaiMonths = array(
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    );
    $thaiMonth = $thaiMonths[$date->format('n') - 1];

    $adYear = $date->format('Y');
    $beYear = convertToBuddhistEra($adYear);

    $thaiDate = $date->format('j') . ' ' . $thaiMonth . ' ' . $beYear;
    return $thaiDate;
}

function convertToBuddhistEra($adYear)
{
    $beYear = $adYear + 543;
    return $beYear;
}

?>
