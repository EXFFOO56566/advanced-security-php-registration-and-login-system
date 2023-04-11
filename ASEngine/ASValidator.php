<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * Class ASValidator
 */
class ASValidator {

    /**
     * @var Instance of ASDatabase class
     */
    private $db;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->db = ASDatabase::getInstance();
    }

    /**
     * Check if provided input is empty. If input is string then it checks if it is empty string.
     * @param $in Input to be checked.
     * @return bool TRUE if input is empty, FALSE otherwise.
     */
    public function isEmpty($in) {
        if ( is_array($in) )
            return empty($in);
        elseif ( $in == '' )
            return true;
        else
            return false;
    }

    /**
     * Check if provided string is longer than provided number of characters.
     * @param $string String to be checked.
     * @param $numOfCharacters Number of characters for comparation.
     * @return bool TRUE if string is longer than provided number of characters, FALSE otherwise.
     */
    public function longerThan($string, $numOfCharacters) {
        if ( strlen($string) > $numOfCharacters )
            return TRUE;
        return FALSE;
    }


    /**
     * Check if email has valid format.
     * @param string $email Email to be checked.
     * @return boolean TRUE if email has valid format, FALSE otherwise.
     */
    public function emailValid($email) {
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
    }

    /**
     * Check if provided username exists.
     * @param $username Username to be checked.
     * @return bool TRUE if username exist, FALSE otherwise.
     */
    public function usernameExist($username) {
        $table  = 'as_users';
        $column = 'username';
        return $this->exist($table, $column, $username);
    }

    /**
     * Check if provided email exists.
     * @param $email Email to be checked.
     * @return bool TRUE if email exist, FALSE otherwise.
     */
    public function emailExist($email) {
        $table  = 'as_users';
        $column = 'email';
        return $this->exist($table, $column, $email);
    }

    /**
     * Check if provided role exists.
     * @param $role Role to be checked.
     * @return bool TRUE if role exist, FALSE otherwise.
     */
    public function roleExist($role) {
        $table  = 'as_user_roles';
        $column = 'role';
        return $this->exist($table, $column, $role);
    }

    /**
     * Check if password reset key is valid.
     * @param $key Key to be validated.
     * @return  boolean TRUE if key is valid, FALSE otherwise
     */
    public function prKeyValid($key) {
        // since it is md5 hash, it has to be 32 characters long
        if ( strlen($key) != 32 )
            return FALSE;

        $result = $this->db->select('SELECT * FROM `as_users` WHERE `password_reset_key` = :k', array(
            'k' => $key
        ));

        // if key doesn't exist in db or it somehow exists more than once, it is not valid key
        if ( count ( $result ) !== 1 )
            return FALSE;

        $result = $result[0];

        // check if key is already used
        if ( $result['password_reset_confirmed'] == 'Y' )
            return FALSE;

        // check if key is expired
        $now = date('Y-m-d H:i:s');
        $requestedAt = $result['password_reset_timestamp'];

        if ( strtotime($now . ' -'.PASSWORD_RESET_KEY_LIFE.' minutes') > strtotime($requestedAt) )
            return FALSE;

        return TRUE;
    }

    /**
     * Check if provided value exist in provided database table and provided db column.
     * @param $table Database table
     * @param $column Database column
     * @param $value Column value
     * @return bool TRUE if value exist in given table and column, FALSE otherwise.
     */
    private function exist($table, $column, $value) {

        $result = $this->db->select("SELECT * FROM `$table` WHERE `$column` = :val", array( 'val' => $value ));

        if ( count ( $result ) > 0 )
            return TRUE;
        else
            return FALSE;
    }

}