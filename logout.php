<?php

include 'ASEngine/AS.php';

ASSession::destroySession();

header('Location: login.php');