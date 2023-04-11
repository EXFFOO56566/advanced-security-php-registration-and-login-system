<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * Class for sending emails.
 */
class ASEmail {
    
    /**
     * Send confirmation email
     * @param string $email Where should confirmation email be send to.
     * @param string $key Confirmation key that should be included in email body.
     */
    function confirmationEmail($email, $key) {
        // include phpmailer's autoloader
        require_once '../vendor/phpmailer/PHPMailerAutoload.php';

        // get instance of PHPMailer (including some additional info)
        $mail = $this->_getMailer();

        // where you want to send confirmation email
        $mail->addAddress($email);

        // link for email confirmation
        $link = REGISTER_CONFIRM . "?k=" . $key;

        // load email HTML template
        $body = file_get_contents('../templates/confirmation-mail.php');

        // replace appropriate placeholders
        $body = str_replace('{{website_name}}',WEBSITE_NAME, $body);
        $body = str_replace('{{link}}',$link, $body);

        // set subject and body
        $mail->Subject = WEBSITE_NAME . " - Registration Confirmation";
        $mail->Body    = $body;

        // try to send the email
        if( ! $mail->send() ) {
            echo 'Message could not be sent. ';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }
    }

    /**
     * Send password reset email.
     * @param string $email Where should password reset email be send to.
     * @param string $key Password reset key that should be included in email body.
     */
    function passwordResetEmail($email, $key) {
        require_once '../vendor/phpmailer/PHPMailerAutoload.php';

        $mail = $this->_getMailer();

        $mail->addAddress($email);

        $link = REGISTER_PASSWORD_RESET . "?k=" . $key;

        $body = file_get_contents('../templates/forgot-password-mail.php');

        $body = str_replace('{{website_name}}',WEBSITE_NAME, $body);
        $body = str_replace('{{link}}',$link, $body);

        $mail->Subject = WEBSITE_NAME . " - Password Reset";
        $mail->Body    = $body;

        if( ! $mail->send() ) {
            echo 'Message could not be sent. ';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }
    }

    
    /* PRIVATE AREA
     =================================================*/

    /**
     * Create and instance of PHPMailer class and prepare it for sending emails.
     * @return PHPMailer Instance of PHPMailer class.
     */
    private function _getMailer() {
        $mail = new PHPMailer;

        // if MAILER constant from config file is set to SMTP
        // configure mailer to send email via SMTP
        if ( MAILER == 'smtp' )
        {
            $mail->isSMTP();

            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
        }

        // tell mailer that we are sending HTML email
        $mail->isHTML(true);

        $mail->From     = 'noreply@' . WEBSITE_DOMAIN;
        $mail->FromName = WEBSITE_NAME;
        $mail->addReplyTo('noreply@' . WEBSITE_DOMAIN, WEBSITE_NAME);

        return $mail;
    }
}

?>
