<?php
//include_once '';
$link = mysqli_connect($host ,$db_user, $db_password, $db_name);

if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
