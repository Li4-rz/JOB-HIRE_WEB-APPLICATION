<?php
    require_once 'core/dbConfig.php';


    session_unset();
    session_destroy();


    header("Location: index.php");
    exit();
?>