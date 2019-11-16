<?php
class Photos extends Controller
{
    private $photo;
    private $class;

    public function __construct($class = 'Photo')
    {
        $this->photo = $this->model('photo');
        $this->class = $class;
    }

    public function index()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->photo->fetchAll($this->class));
    }

    public function show($id)
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->photo->fetch($id, $this->class));
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin: *");

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => $_POST['title'],
                'title_error' => '',
                'image_error' => ''
            ];

            if (empty($data['title']))
                $data['title_error'] = 'Title cannot be empty!';
            if (count($_FILES['images']['name']) == 0)
                $data['image_error'] = 'Please select an image.';

            if (empty($data['title_error']) && empty($data['image_error'])) {
                $this->photo->store($this->class, null, $data['title']);
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode($data);
            }
        } else {
            redirect('/');
        }
    }

    public function destroy($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin: *");

            if ($this->photo->_destroy($id) > 0)
                echo (json_encode(['status' => 200]));
            else
                echo (json_encode(['status' => 500]));
        } else {
            redirect('/');
        }
    }
    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin: *");

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => $_POST['title'],
                'title_error' => '',
            ];

            if (empty($data['title']))
                $data['title_error'] = 'Title cannot be empty!';

            if (empty($data['title_error'])) {
                $this->photo->update($id, $data);
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode($data);
            }
        } else {
            redirect('/');
        }
    }
}
