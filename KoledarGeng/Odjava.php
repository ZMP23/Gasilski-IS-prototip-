<?php

session_start();

session_destroy();

header("Location: PrijavnaStran.php");
exit;