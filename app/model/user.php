<?php

namespace App\Model;

use PDO;
use App\Model\Session;
use App\Connection\Database as Db;

class User
{

    const KEY_HASH = 'n12o9sjdfolah1';

    private $conn;
    private $token;
    public function __construct()
    {
        $db = new Db();
        $this->conn = $db->conn;
    }

    public function generateToken($id)
    {
        $today = date('Y-m-d');
        $this->token = hash('sha256', $today . self::KEY_HASH . $id);
    }

    public function checkToken($token, $id)
    {
        $this->generateToken($id);
        if ($token != $this->token) {
            return false;
        }

        return true;
    }

    public function authenticate($user, $pass)
    {
        $stmt = $this->conn->prepare('SELECT id, password FROM users WHERE username = :username');
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        if (password_verify($pass, $result['password'])) {
            return $result['id']    ;
        }

        return false;
    }

    public function login($user, $pass)
    {
        $session = new Session();

        if ($id = $this->authenticate($user, $pass)) {
            $this->generateToken($id);
            $session->set('user_token', $this->token);
            $session->set('user_id', $id);

            return true;
        }

        return false;
    }

    public function logout()
    {
        $session = new Session();

        $session->clear();
    }
}
