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
        return $this->view('Index');
    }

    public function admin()
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
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && !isAuth()) {
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
        } else {
            return redirect('/');
        }
    }

    public function logout()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth()) {
            unset($_SESSION['id']);
            unset($_SESSION['role']);
            session_destroy();
            echo json_encode(array(
                "status" => 200
            ));
            return;
        } else {
            return redirect('/');
        }
    }

    public function update()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth() && isAdmin()) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $credentials = array(
                'id' => trim($_POST['id']),
                'name' => trim(ucwords($_POST['name'])),
                'role' => $_POST['role'],
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'id_err' => '',
                'confirm_password_err' => '',
                'name_err' => '',
                'role_err' => ''
            );

            if (empty($credentials['id'])) {
                $credentials['id_err'] = 'Please select an account.';
            }
            if (empty($credentials['name'])) {
                $credentials['name_err'] = 'Please provide a name.';
            }
            if (empty($credentials['role'])) {
                $credentials['role_err'] = 'Please provide a role.';
            }


            if (empty($credentials['id_err']) && empty($credentials['role_err']) && empty($credentials['name_err'])) {
                // '' == '', 'password' == 'passwprd' 
                if ($credentials['password'] == $credentials['confirm_password']) {
                    if ($this->user->update($credentials)) {
                        echo json_encode(array(
                            'status' => 200
                        ));
                        return;
                    }
                } else {
                    $credentials['confirm_password_err'] = 'Please provide a matching password.';
                }
            } else {
                echo json_encode($credentials);
                return;
            }
        } else {
            return redirect('/');
        }
    }

    //

    // All Users
    public function users()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if (isAuth() && isAdmin()) {
            echo json_encode($this->user->fetchAll());
            return;
        } else {
            return redirect('/');
        }
    }

    public function destroy($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth() && isAdmin()) {
            echo json_encode($this->user->destroy($id));
            return;
        } else {
            return redirect('/');
        }
    }

    public function create()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == 'POST' && isAuth() && isAdmin()) {
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'password' => trim($_POST['password']),
                'name_error' => false,
                'email_error' => false,
                'password_error' => false,
            ];

            if (empty($data['name']))
                $data['name_error'] = true;
            if (empty($data['email']))
                $data['email_error'] = true;
            if (empty($data['password']))
                $data['password_error'] = true;

            if (empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error'])) {
                echo json_encode($this->user->create($data));
                return;
            }
        } else {
            return redirect('/');
        }
    }
}
