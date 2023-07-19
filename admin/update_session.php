<!-- update_session.php -->
<?php
session_start();

if (isset($_POST['selectedIds']) && is_array($_POST['selectedIds'])) {
    $_SESSION['selectedStdIds'] = $_POST['selectedIds'];
}