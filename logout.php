<?php
session_start();
session_destroy();
header("Location: logout.html"); // Redirect to the login page or wherever you want to send them after logging out.
exit();
