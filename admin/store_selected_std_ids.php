<?php
session_start();

if (isset($_POST['stdIds'])) {
    $_SESSION['selectedStdIds'] = $_POST['stdIds'];
    echo 'Selected std_ids stored in session.';
} else {
    echo 'No std_ids were provided.';
}
?>
