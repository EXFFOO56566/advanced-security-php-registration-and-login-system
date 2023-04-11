<?php

include '../ASEngine/ASDatabase.php';


//this will return error message if it can't connect to database
$db = new ASDatabase(
            'mysql', 
            $_POST['db_host'], 
            $_POST['db_name'], 
            $_POST['db_user'], 
            $_POST['db_pass']
        );