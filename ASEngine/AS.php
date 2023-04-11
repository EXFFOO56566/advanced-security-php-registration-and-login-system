<?php
include_once 'ASConfig.php';
include_once 'ASSession.php';
include_once 'ASValidator.php';
include_once 'ASLang.php';
include_once 'ASRole.php';
include_once 'ASDatabase.php';
include_once 'ASEmail.php';
include_once 'ASLogin.php';
include_once 'ASRegister.php';
include_once 'ASUser.php';
include_once 'ASComment.php';

$db = ASDatabase::getInstance();

ASSession::startSession();

$login    = new ASLogin();
$register = new ASRegister();
$mailer   = new ASEmail();

if ( isset ( $_GET['lang'] ) )
	ASLang::setLanguage($_GET['lang']);

