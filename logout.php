<?php
session_start();
session_destroy();
header("Location: default.php"); // or wherever you want to redirect
exit();