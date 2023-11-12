<?php
namespace App\Controllers;

use App\Model\User;
class Controller
{

    public $errMsg;
    public function view($view, $data = [])
    {
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }

    public function checkToken()
    {
        $user = new User();
        
        $token = $_COOKIE['user_token'] ?? '';
        $userId = $_COOKIE['user_id'] ?? '';
        if (!$user->checkToken($token, $userId)){
            header("Location:../user/login");
        }

        return $userId;
    }

    public function getRequest()
    {
        // Ambil raw request body
        $json = file_get_contents('php://input');
        
        return json_decode($json, true);
    }

    public function sendResponse($code, $message, $data = [])
    {
        echo json_encode([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    public function validateRequest($requests, $params = array())
    {
        $valid = true;
        $invalidParamName = '';

        foreach ($params as $param) {
            if (!isset($requests[$param]) || empty($requests[$param])) {
                $valid = false;
                $invalidParamName = $param;
                break;
            }
        }

        if (!$valid) {
            $this->errMsg = "Failed : $invalidParamName is required";
        }

        return $valid;
    }
}
