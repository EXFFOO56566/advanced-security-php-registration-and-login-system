<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * User login class.
 *
 */
class ASLogin {

    /**
     * @var Instance of ASDatabase class
     */
    private $db = null;

    /**
     * Class constructor
     */
    function __construct() {
       $this->db = ASDatabase::getInstance();
    }

    /**
     * Log in user with provided id.
     * @param $id
     */
    public function byId($id) {
        if ( $id != 0 && $id != '' && $id != null ) {
            $this->_updateLoginDate($id);
            ASSession::set("user_id", $id);
            if(LOGIN_FINGERPRINT == true)
                ASSession::set("login_fingerprint", $this->_generateLoginString ());
        }
    }
    
    
    /**
     * Check if user is logged in.
     * @return boolean TRUE if user is logged in, FALSE otherwise.
     */
    public function isLoggedIn() {
        //if $_SESSION['user_id'] is not set return false
        if(ASSession::get("user_id") == null)
             return false;
        
        //if enabled, check fingerprint
        if(LOGIN_FINGERPRINT == true) {
            $loginString  = $this->_generateLoginString();
            $currentString = ASSession::get("login_fingerprint");
            if($currentString != null && $currentString == $loginString)
                return true;
            else  {
                //destroy session, it is probably stolen by someone
                $this->logout();
                return false;
            }
        }
        
        //if you got to this point, user is logged in
        return true;        
    }
    
    
    /**
     * Login user with given username and password.
     * @param string $username User's username.
     * @param string $password User's password.
     * @return boolean TRUE if login is successful, FALSE otherwise
     */
    public function userLogin($username, $password) {
        //validation
        $errors = $this->_validateLoginFields($username, $password);
        if(count($errors) != 0) {
            $result = implode("<br />", $errors);
            echo $result;
        }
        
        //protect from brute force attack
        if($this->_isBruteForce()) {
            echo ASLang::get('brute_force');
            return;
        }
        
        //hash password and get data from db
        $password = $this->_hashPassword($password);
        $result = $this->db->select(
                    "SELECT * FROM `as_users`
                     WHERE `username` = :u AND `password` = :p",
                     array(
                       "u" => $username,
                       "p" => $password
                     )
                  );
        
        if(count($result) == 1) 
        {
            // check if user is confirmed
            if($result[0]['confirmed'] == "N") {
                echo ASLang::get('user_not_confirmed');
                return false;
            }

            // check if user is banned
            if($result[0]['banned'] == "Y") {
                // increase attempts to prevent touching the DB every time
                $this->increaseLoginAttempts();

                // return message that user is banned
                echo ASLang::get('user_banned');
                return false;
            }

            //user exist, log him in if he is confirmed
            $this->_updateLoginDate($result[0]['user_id']);
            ASSession::set("user_id", $result[0]['user_id']);
            if(LOGIN_FINGERPRINT == true)
                ASSession::set("login_fingerprint", $this->_generateLoginString ());
            
            return true;
        }
        else {
            //wrong username/password combination
            $this->increaseLoginAttempts();
            echo ASLang::get('wrong_username_password');
            return false;
        }
    }
    
    /**
     * Increase login attempts from specific IP address to preven brute force attack.
     */
    public function increaseLoginAttempts() {
        $date    = date("Y-m-d");
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $table   = 'as_login_attempts';
       
        //get current number of attempts from this ip address
        $loginAttempts = $this->_getLoginAttempts();
        
        //if they are greater than 0, update the value
        //if not, insert new row
        if($loginAttempts > 0)
            $this->db->update (
                        $table, 
                        array( "attempt_number" => $loginAttempts + 1 ), 
                        "`ip_addr` = :ip_addr AND `date` = :d", 
                        array( "ip_addr" => $user_ip, "d" => $date)
                      );
        else
            $this->db->insert($table, array(
                "ip_addr" => $user_ip,
                "date"    => $date
            ));
    }
    
    /**
     * Log out user and destroy session.
     */
    public function logout() {
        ASSession::destroySession();
    }

      /**
     * Check if someone is trying to break password with brute force attack.
     * @return TRUE if number of attemts are greater than allowed, FALSE otherwise.
     */
    public function _isBruteForce() {
        $loginAttempts = $this->_getLoginAttempts();
        if($loginAttempts > LOGIN_MAX_LOGIN_ATTEMPTS)
            return true;
        else
            return false;
    }
    
    
        
    /* PRIVATE AREA
     =================================================*/
    
    /**
     * Validate login fields
     * @param string $username User's username.
     * @param string $password User's password.
     * @return array Array with errors if there are some, empty array otherwise.
     */
    private function _validateLoginFields($username, $password) {
        $id     = $_POST['id'];
        $errors = array();
        
        if($username == "")
            $errors[] = ASLang::get('username_required');
        
        if($password == "")
            $errors[] = ASLang::get('password_required');
        
        return $errors;
    }
    
    /**
     * Generate string that will be used as fingerprint. 
     * This is actually string created from user's browser name and user's IP 
     * address, so if someone steal users session, he won't be able to access.
     * @return string Generated string.
     */
    private function _generateLoginString() {
        $userIP = $_SERVER['REMOTE_ADDR'];
        $userBrowser = $_SERVER['HTTP_USER_AGENT'];
        $loginString = hash('sha512',$userIP.$userBrowser);
        return $loginString;
    }
    
    
    /**
     * Get number of login attempts from user's IP address.
     * @return int Number of login attempts.
     */
    private function _getLoginAttempts() {
        $date = date("Y-m-d");
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
         $query = "SELECT `attempt_number`
                   FROM `as_login_attempts`
                   WHERE `ip_addr` = :ip AND `date` = :date";
                      
         
        $result = $this->db->select($query, array(
            "ip"    => $user_ip,
            "date"  => $date
        ));
        if(count($result) == 0)
            return 0;
        else
            return intval($result[0]['attempt_number']);
    }
    
      
    /**
     * Hash user's password using salt.
     * @param string $password Unhashed password.
     * @return string Hashed password
     */
    private function _hashPassword($password) {
        $register = new ASRegister();
        return $register->hashPassword($password);
    }
    
    
    /**
     * Update database with login date and time when this user is logged in.
     * @param int $userid Id of user that is logged in.
     */
    private function _updateLoginDate($userid) {
        $this->db->update(
                    "as_users",
                    array("last_login" => date("Y-m-d H:i:s")),
                    "user_id = :u",
                    array( "u" => $userid)
                );
    }
    
}

