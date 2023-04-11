<?php

extract($_POST);

if ( $smtp_port == '' ) $smtp_port = 0;

$website_domain = rtrim($website_domain, '/');

$admin_email = 'admin@' . str_replace(array('http://', 'https://'), array('', '') ,$website_domain);

$website_domain = strpos($website_domain, 'http://') === 0 || strpos($website_domain, 'https://') === 0 ?
    $website_domain : 'http://' . $website_domain;

$scriptUrl = $website_domain.dirname(dirname($_SERVER['PHP_SELF'])) . "/";

$output = "<?php".PHP_EOL.PHP_EOL;

$output .= "//BOOTSTRAP".PHP_EOL.PHP_EOL;
$output .= "define('BOOTSTRAP_VERSION', $bootstrap);".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//WEBSITE".PHP_EOL.PHP_EOL;
$output .= "define('WEBSITE_NAME', \"$website_name\");".PHP_EOL.PHP_EOL;
$output .= "define('WEBSITE_DOMAIN', \"$website_domain\");".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//DATABASE CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('DB_HOST', \"$db_host\"); ".PHP_EOL.PHP_EOL;
$output .= "define('DB_TYPE', \"mysql\"); ".PHP_EOL.PHP_EOL;
$output .= "define('DB_USER', \"$db_user\"); ".PHP_EOL.PHP_EOL;
$output .= "define('DB_PASS', \"$db_pass\"); ".PHP_EOL.PHP_EOL;
$output .= "define('DB_NAME', \"$db_name\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//SESSION CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('SESSION_SECURE', $session_secure);   ".PHP_EOL.PHP_EOL;
$output .= "define('SESSION_HTTP_ONLY', $session_http_only);".PHP_EOL.PHP_EOL;
$output .= "define('SESSION_REGENERATE_ID', $session_regenerate_id);   ".PHP_EOL.PHP_EOL;
$output .= "define('SESSION_USE_ONLY_COOKIES', $session_use_only_cookies);".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//LOGIN CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('LOGIN_MAX_LOGIN_ATTEMPTS', $login_max_login_attempts); ".PHP_EOL.PHP_EOL;
$output .= "define('LOGIN_FINGERPRINT', $login_fingerprint); ".PHP_EOL.PHP_EOL;
$output .= "define('SUCCESS_LOGIN_REDIRECT', \"$redirect_after_login\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//PASSWORD CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('PASSWORD_ENCRYPTION', \"$encryption\"); //available values: \"sha512\", \"bcrypt\"".PHP_EOL.PHP_EOL;
$output .= "define('PASSWORD_BCRYPT_COST', \"$bcrypt_cost\"); ".PHP_EOL.PHP_EOL;
$output .= "define('PASSWORD_SHA512_ITERATIONS', $sha512_iterations); ".PHP_EOL.PHP_EOL;
$output .= "define('PASSWORD_SALT', \"".  randomString(22)."\"); //22 characters to be appended on first 7 characters that will be generated using PASSWORD_ info above".PHP_EOL.PHP_EOL;
$output .= "define('PASSWORD_RESET_KEY_LIFE', $prk_life); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//REGISTRATION CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('MAIL_CONFIRMATION_REQUIRED', $mail_confirm_required); ".PHP_EOL.PHP_EOL;
$output .= "define('REGISTER_CONFIRM', \"".$scriptUrl."confirm.php\"); ".PHP_EOL.PHP_EOL;
$output .= "define('REGISTER_PASSWORD_RESET', \"".$scriptUrl."passwordreset.php\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//EMAIL SENDING CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('MAILER', \"$mailer\"); ".PHP_EOL.PHP_EOL;
$output .= "define('SMTP_HOST', \"$smtp_host\"); ".PHP_EOL.PHP_EOL;
$output .= "define('SMTP_PORT', $smtp_port); ".PHP_EOL.PHP_EOL;
$output .= "define('SMTP_USERNAME', \"$smtp_username\"); ".PHP_EOL.PHP_EOL;
$output .= "define('SMTP_PASSWORD', \"$smtp_password\"); ".PHP_EOL.PHP_EOL;
$output .= "define('SMTP_ENCRYPTION', \"$smtp_enc\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "//SOCIAL LOGIN CONFIGURATION".PHP_EOL.PHP_EOL;
$output .= "define('SOCIAL_CALLBACK_URI', \"".$scriptUrl."vendor/hybridauth/\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "// GOOGLE".PHP_EOL.PHP_EOL;
$output .= "define('GOOGLE_ENABLED', $google_login); ".PHP_EOL.PHP_EOL;
$output .= "define('GOOGLE_ID', \"$gp_id\"); ".PHP_EOL.PHP_EOL;
$output .= "define('GOOGLE_SECRET', \"$gp_secret\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "// FACEBOOK".PHP_EOL.PHP_EOL;
$output .= "define('FACEBOOK_ENABLED', $facebook_login); ".PHP_EOL.PHP_EOL;
$output .= "define('FACEBOOK_ID', \"$fb_id\"); ".PHP_EOL.PHP_EOL;
$output .= "define('FACEBOOK_SECRET', \"$fb_secret\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "// TWITTER".PHP_EOL.PHP_EOL;
$output .= "// NOTE: Twitter api for authentication doesn't provide users email address!".PHP_EOL;
$output .= "// So, if you email address is strictly required for all users, consider disabling twitter login option.".PHP_EOL.PHP_EOL;
$output .= "define('TWITTER_ENABLED', $twitter_login); ".PHP_EOL.PHP_EOL;
$output .= "define('TWITTER_KEY', \"$tw_key\"); ".PHP_EOL.PHP_EOL;
$output .= "define('TWITTER_SECRET', \"$tw_secret\"); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$output .= "// TRANSLATION".PHP_EOL.PHP_EOL;
$output .= "define('DEFAULT_LANGUAGE', 'en'); ".PHP_EOL.PHP_EOL;
$output .= PHP_EOL;

$file = '../ASEngine/ASConfig.php';
$handle = fopen($file, 'w');
fwrite($handle, $output);
fclose($handle);

include "../ASEngine/AS.php";

$query = "CREATE TABLE IF NOT EXISTS `as_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `email` varchar(40) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `confirmation_key` varchar(40) NOT NULL,
  `confirmed` enum('Y','N') NOT NULL default 'N',
  `password_reset_key` varchar(250) NOT NULL default '',
  `password_reset_confirmed` enum('Y','N') NOT NULL default 'N',
  `password_reset_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `register_date` date NOT NULL,
  `user_role` int(4) NOT NULL default 1,
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `banned` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `as_social_logins` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `provider` varchar(50) DEFAULT 'email',
    `provider_id` varchar(250) DEFAULT NULL,
    `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `as_login_attempts` (
  `id_login_attempts` int(11) NOT NULL AUTO_INCREMENT,
  `ip_addr` varchar(20) NOT NULL,
  `attempt_number` int(11) NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  PRIMARY KEY (`id_login_attempts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `as_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_by` int(11) NOT NULL,
  `posted_by_name` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  `post_time` datetime NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `as_user_details` (
  `id_user_details` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(35) NOT NULL DEFAULT '',
  `last_name` varchar(35) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user_details`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `as_user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `as_user_roles` (`role_id`, `role`) VALUES
(1, 'user'),
(2, 'editor'),
(3, 'admin');

INSERT INTO `as_users` (`user_id`, `email`, `username`, `password`, `confirmation_key`, 
                        `confirmed`, `password_reset_key`, `password_reset_confirmed`, 
                        `user_role`, `register_date`) 
VALUES (1,'$admin_email', 'admin','','', 'Y', '', 'N', 3, '".date("Y-m-d")."');";

$db->exec($query);

//function for creating salt
function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

//update admin password to admin123
$ASRegister = new ASRegister();

//hash password with sha512 without salt because it will be transfered from
//client side hashed with sha512
$adminPass = hash("sha512", "admin123");

//hash password using salt
$adminPass = $ASRegister->hashPassword($adminPass);

$db->update("as_users", array( "password" => $adminPass ), "`username` = 'admin'");


//Advanced Security installed successfully!
echo "success";
