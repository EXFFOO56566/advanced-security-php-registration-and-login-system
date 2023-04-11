<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * Class ASRole
 * Description: Class for manipulating with user roles.
 */
class ASRole {

    /**
     * @var Instance of ASDatabase class
     */
    private $db = null;

    /**
     * @var Instance of ASValidator class
     */
    private $validator;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->db = ASDatabase::getInstance();
        $this->validator = new ASValidator();
    }

    /**
     * Get role id of role that have provided role name.
     * @param $name Role name
     * @return Role id if role with provided role name exist, null otherwise.
     */
    public function getId($name) {
        $result = $this->db->select("SELECT `role_id` FROM `as_user_roles` WHERE `role` = :r", array( 'r' => $name ));
        if ( count ( $result ) > 0 )
            return $result[0]['role_id'];
        else
            return null;
    }

    /**
     * Get role name of role with provided id.
     * @param $id Role id
     * @return Role Name if role with provided role id exist, null otherwise.
     */
    public function name($id) {
        $result = $this->db->select("SELECT `role` FROM `as_user_roles` WHERE `role_id` = :id", array( 'id' => $id ));
        if ( count ( $result ) > 0 )
            return $result[0]['role_id'];
        else
            return null;
    }


    /**
     * Add new role into db.
     * @param $name Role name
     * @return array Response array that contains status (error or success) and message.
     */
    public function add($name) {
        $result = array();

        if ( ! $this->validator->roleExist($name) )
        {
            // role doesn't exist, create it
            $this->db->insert("as_user_roles", array("role" => strtolower(strip_tags($_POST['role']))));
            $result = array(
                "status"   => "success",
                "roleName" => strip_tags($_POST['role']),
                "roleId"   => $this->db->lastInsertId()
            );
        }
        else
        {
            // role exist, return error message
            $result = array(
                "status" => "error",
                "message" => ASLang::get('role_taken')
            );
        }

        return $result;
    }

    /**
     * Delete role with provided id.
     * @param $id Role id
     */
    public function delete($id) {
        //default user roles can't be deleted
        if(in_array($_POST['roleId'], array(1,2,3)) )
            exit();

        $this->db->delete("as_user_roles", "role_id = :id", array( "id" => $id ));

        $this->db->update("as_users", array( 'user_role' => "1" ), "user_role = :r", array( "r" => $id ) );
    }

} 