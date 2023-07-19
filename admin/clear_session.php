<?php
session_start();

// Clear the selectedStdIds session if it exists
if (isset($_SESSION['selectedStdIds'])) {
    unset($_SESSION['selectedStdIds']);
}
?>
