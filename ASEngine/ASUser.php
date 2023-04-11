<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * User class.
 */
class ASUser {

    /**
     * @var ID of user represented by this class
     */
    private $userId;

    /**
     * @var Instance of ASDatabase class
     */
    private $db = null;

    /**
     * Class constructor
     * @param $userId ID of user that will be represented by this class
     */
    function __construct($userId) {
        //update local user id with given user id
        $this->userId = $userId;

        //connect to database
        $this->db = ASDatabase::getInstance();
    }

    /**
     * Get all user details including email, username and last_login
     * @return User details or null if user with given id doesn't exist.
     */
    public function getAll() {
        $query = "SELECT `as_users`.`email`, `as_users`.`username`,`as_users`.`last_login`, `as_user_details`.*
                    FROM `as_users`, `as_user_details`
                    WHERE `as_users`.`user_id` = :id
                    AND `as_users`.`user_id` = `as_user_details`.`user_id`";

        $result = $this->db->select($query, array( 'id' => $this->userId ));

        if ( count ( $result ) > 0 )
            return $result[0];
        else
            return null;
    }

    /**
     * Add new user using data provided by administrator from admin panel.
     * @param $postData All data filled in administrator's "Add User" form
     * @return array Result that contain status (error or success) and message.
     */
    public function add( $postData ) {

        // prepare required objects and arrays
        $result = array();
        $reg = new ASRegister();
        $errors = $reg->validateUser($postData, false);

        // if count ( $errors ) > 0 means that validation didn't passed and that there are errors
        if ( count ($errors) > 0 )
            $result = array(
                "status" => "error",
                "errors" => $errors
            );
        else {
            //validation passed
            $data = $postData['userData'];

            // insert user login info
            $this->db->insert('as_users',  array (
                'email'         => $data['email'],
                'username'      => $data['username'],
                'password'      => $reg->hashPassword($data['password']),
                'confirmed'     => 'Y',
                'register_date' => date('Y-m-d H:i:s')
            ));

            // get user id
            $id = $this->db->lastInsertId();

            // insert users details
            $this->db->insert('as_user_details', array (
                'user_id'    => $id,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'phone'      => $data['phone'],
                'address'    => $data['address']
            ) );

            // generate response
            $result = array (
                "status" => "success",
                "msg"    => ASLang::get("user_added_successfully")
            );
        }

        return $result;
    }

    /**
     * Update user's details
     * @param $data User data from admin's "edit user" form
     */
    public function updateUser($data) {

        // validate data
        $errors = $this->_validateUserUpdate($data);

        if ( count ( $errors ) > 0 )
            echo json_encode(array(
                "status" => "error",
                "errors" => $errors
            ));
        else
        {
            // validation passed, update user

            $userData = $data['userData'];
            $currInfo = $this->getInfo();

            $userInfo = array();

            // update user's email and username only if they are changed, skip them otherwise
            if ( $currInfo['email'] != $userData['email'] )
                $userInfo['email'] = $userData['email'];

            if ( $currInfo['username'] != $userData['username'] )
                $userInfo['username'] = $userData['username'];

            // update password only if "password" field is filled
            // and password is different than current password
            if ( $userData['password'] != hash('sha512','') ) {
                $password = $this->_hashPassword($userData['password']);
                if ( $currInfo['password'] != $password )
                    $userInfo['password'] = $password;
            }

            if ( count($userInfo) > 0 )
                $this->updateInfo($userInfo);

            $this->updateDetails(array(
                'first_name' => $userData['first_name'],
                'last_name'  => $userData['last_name'],
                'phone'      => $userData['phone'],
                'address'    => $userData['address']
            ));

            echo json_encode(array(
                "status" => "success",
                "msg" => ASLang::get("user_updated_successfully")
            ));
        }
    }
    
    /**
     * Set user id if new one is provided, return old one otherwise.
     * @param int $newId New user id.
     * @return int Returns new user id if it is provided, old user id otherwise.
     */
    public function id($newId = null) {
        if($newId != null)
            $this->userId = $newId;
        return $this->userId;
    }

    /**
     * Check if user is admin.
     * @return boolean TRUE if user is admin, FALSE otherwise.
     */
    public function isAdmin() {
        if ( $this->userId == null )
            return false;

        $role = $this->getRole();
        if($role == "admin")
            return true;
        return false;
    }

    /**
     * Updates user's password.
     * @param string $oldPass Old password.
     * @param string $newPass New password.
     */
    public function updatePassword($oldPass,$newPass) {
        //hash both passwords
        $oldPass = $this->_hashPassword($oldPass);
        $newPass = $this->_hashPassword($newPass);
        
        //get user info (email, password etc)
        $info = $this->getInfo();
        
        //update if entered old password is correct
        if($oldPass == $info['password'])
            $this->updateInfo(array( "password" => $newPass ));
        else
            echo ASLang::get('wrong_old_password');
    }


    /**
     * Changes user's role. If user's role was editor it will be set to user and vice versa.
     * @return string New user role.
     */
    public function changeRole() {
        $role = $_POST['role'];

        $result = $this->db->select("SELECT * FROM `as_user_roles` WHERE `role_id` = :r", array( "r" => $role ));

        if(count($result) == 0)
            return;

        $this->updateInfo(array( "user_role" => $role ));

        return $result[0]['role'];
    }

    /**
     * Get current user's role.
     * @return string Current user's role.
     */
    public function getRole() {
        $result = $this->db->select(
                      "SELECT `as_user_roles`.`role` as role 
                       FROM `as_user_roles`,`as_users`
                       WHERE `as_users`.`user_role` = `as_user_roles`.`role_id`
                       AND `as_users`.`user_id` = :id",
                       array( "id" => $this->userId)
                    );

        return $result[0]['role'];
    }

    /**
     * Get basic user info provided during registration.
     * @return array User info array.
     */
    public function getInfo() {
        $result = $this->db->select(
                    "SELECT * FROM `as_users` WHERE `user_id` = :id",
                    array ("id" => $this->userId)
                  );
        if ( count($result) > 0 )
            return $result[0];
        else
            return null;
    }


    /**
     * Updates user info.
     * @param array $updateData Associative array where keys are database fields that need
     * to be updated and values are new values for provided database fields.
     */
    public function updateInfo($updateData) {
        $this->db->update(
                    "as_users", 
                    $updateData, 
                    "`user_id` = :id",
                    array( "id" => $this->userId )
               );
    }

    
    /**
     * Get user details (First Name, Last Name, Address and Phone)
     * @return array User details array.
     */
    public function getDetails() {
        $result = $this->db->select(
                    "SELECT * FROM `as_user_details` WHERE `user_id` = :id",
                    array ("id" => $this->userId)
                  );

        if(count($result) == 0)
            return array(
                "first_name" => "",
                "last_name"  => "",
                "address"    => "",
                "phone"      => "",
                "empty"      => true
            );

        return $result[0];
    }

    
    /**
     * Updates user details.
     * @param array $details Associative array where keys are database fields that need
     * to be updated and values are new values for provided database fields.
     */
    public function updateDetails($details) {
        $currDetails = $this->getDetails();
        if(isset($currDetails['empty'])) {
            $details["user_id"] = $this->userId;
            $this->db->insert("as_user_details", $details);
        }
        else
            $this->db->update (
                "as_user_details",
                $details,
                "`user_id` = :id",
                array( "id" => $this->userId )
            );
    }

    
    /**
     * Delete user, all his comments and connected social accounts.
     */
    public function deleteUser() {
        $this->db->delete("as_users", "user_id = :id", array( "id" => $this->userId ));
        $this->db->delete("as_user_details","user_id = :id", array( "id" => $this->userId ));
        $this->db->delete("as_comments","posted_by = :id", array( "id" => $this->userId ));
        $this->db->delete("as_social_logins","user_id = :id", array( "id" => $this->userId ));
    }


     /* PRIVATE AREA
     =================================================*/

    /**
     * Validate data provided during user update
     * @param $data
     * @return array
     */
    private function _validateUserUpdate($data) {
        $id     = $data['fieldId'];
        $user   = $data['userData'];
        $errors = array();
        $validator = new ASValidator();

        $userInfo = $this->getInfo();
        if ( $userInfo == null ) {
            $errors[] = array(
                "id"    => $id['email'],
                "msg"   => ASLang::get('user_dont_exist')
            );
            return $errors;
        }

        //check if email is not empty
        if( $validator->isEmpty($user['email']) )
            $errors[] = array(
                "id"    => $id['email'],
                "msg"   => ASLang::get('email_required')
            );

        //check if username is not empty
        if( $validator->isEmpty($user['username']) )
            $errors[] = array(
                "id"    => $id['username'],
                "msg"   => ASLang::get('username_required')
            );

        //check if password and confirm password are the same
        if( ! $user['password'] == hash('sha512','') && ($user['password'] != $user['confirm_password'] ))
            $errors[] = array(
                "id"    => $id['confirm_password'],
                "msg"   => ASLang::get('passwords_dont_match')
            );

        //check if email format is correct
        if( ! $validator->emailValid($user['email']) )
            $errors[] = array(
                "id"    => $id['email'],
                "msg"   => ASLang::get('email_wrong_format')
            );

        //check if email is available
        if($user['email'] != $userInfo['email'] && $validator->emailExist($user['email']))
            $errors[] = array(
                "id"    => $id['email'],
                "msg"   => ASLang::get('email_taken')
            );

        //check if username is available
        if($user['username'] != $userInfo['username'] && $validator->usernameExist($user['username']) )
            $errors[] = array(
                "id"    => $id['username'],
                "msg"   => ASLang::get('username_taken')
            );

        return $errors;
    }
    
    /**
     * Hash provided password.
     * @param string $password Password that needs to be hashed.
     * @return string Hashed password.
     */
    private function _hashPassword($password) {
        $register = new ASRegister();
        return $register->hashPassword($password);
    }
}
