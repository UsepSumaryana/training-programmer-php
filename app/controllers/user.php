<?php

use App\Controllers\Controller;
use App\Model\User as UserModel;
use App\Model\View;

class User extends Controller
{

    public function login()
    {
        if(isset($_POST['submit'])) {
            $result = $this->signIn();
            $this->view('login', $result);
        }
        $this->view('login');
    }

    public function signIn()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new UserModel();

        if ($user->login($username, $password)) {
            header('location:../todos');
        } else {
            return [
                'errorMsg' => 'Invalid username Password'
            ];
        }
    }

    public function signOut()
    {
        $user = new UserModel();

        $user->logout();

        header('location:../todos');
    }

}
