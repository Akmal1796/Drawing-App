<?php
session_start();
session_destroy();
header("Location: logout.html"); // Redirect to the login page
exit();
