<?php
class Pages extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = $this->model('user');
    }

    public function index()
    {
        if (!isAuth()) {
            $credentials = array(
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            );
            return $this->view('Home', $credentials);
        } else {
            return $this->view('Home');
        }
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $credentials = array(
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'email_err' => '',
                'password_err' => ''
            );

            if (empty($credentials['email'])) {
                $credentials['email_err'] = 'Please provide an email.';
            }
            if (empty($credentials['password'])) {
                $credentials['password_err'] = 'Please provide a password.';
            }

            if (empty($credentials['email_err']) && empty($credentials['password_err'])) {
                if (!$this->user->findByEmail($credentials['email'])) {
                    $credentials['email_err'] = 'Incorrect Email';
                    return $this->view('Home', $credentials);
                } else {
                    if ($this->user->login($credentials['email'], $credentials['password'])) {
                        return $this->view('Home');
                    } else {
                        $credentials['password_err'] = 'Incorrect Password';
                        return $this->view('Home', $credentials);
                    }
                }
            } else {
                return $this->view('Home', $credentials);
            }
        }
    }

    public function logout()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            unset($_SESSION['id']);
            session_destroy();
            return redirect('/');
        }
    }
}
