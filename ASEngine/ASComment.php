<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * Comments class.
 */
class ASComment {

    /**
     * @var Instance of ASDatabase class itself
     */
    private $db = null;

    /**
     * Class constructor
     */
    function __construct() {
        $this->db = ASDatabase::getInstance();
    }


    /**
     * Inserts comment into database.
     * @param int $userId Id of user who is posting the comment.
     * @param string $comment Comment text.
     * @return string JSON encoded string that consist of 3 fields:
     * user,comment and postTime
     */
    public function insertComment($userId, $comment) {
        $user     = new ASUser($userId);
        $userInfo = $user->getInfo();
        $datetime = date("Y-m-d H:i:s");

        $this->db->insert("as_comments",  array(
            "posted_by"      => $user->id(),
            "posted_by_name" => $userInfo['username'],
            "comment"        => strip_tags($comment),
            "post_time"      => $datetime
        ));
        $result = array(
            "user"      => $userInfo['username'],
            "comment"   => stripslashes( strip_tags($comment) ),
            "postTime"  => $datetime
        );
        return json_encode($result);
    }



    /**
     * Return all comments left by one user.
     * @param int $userId Id of user.
     * @return array Array of all user's comments.
     */
    public function getUserComments($userId) {
        $result = $this->db->select(
                    "SELECT * FROM `as_comments` WHERE `user_id` = :id",
                    array ("id" => $userId)
                  );

        return $result;
    }


    /**
     * Return last $limit (default 7) comments from database.
     * @param int $limit Required number of comments.
     * @return array Array of comments.
     */
    public function getComments($limit = 7) {
        return $this->db->select("SELECT * FROM `as_comments` ORDER BY `post_time` DESC LIMIT $limit");
    }
}
