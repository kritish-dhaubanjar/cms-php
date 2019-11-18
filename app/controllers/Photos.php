<?php
class Photos extends Controller
{
    private $photo;
    private $class;

    public function __construct($class = 'Photo')
    {
        $this->photo = $this->model('photo');
        $this->class = $class;
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
    }

    public function page($page)
    {
        echo json_encode($this->photo->paginate($page));
        return;
    }

    public function index()
    {
        echo json_encode($this->photo->fetchAll($this->class));
        return;
    }

    public function show($id)
    {
        echo json_encode($this->photo->fetch($id, $this->class));
        return;
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {
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
                return;
            } else {
                echo json_encode($data);
                return;
            }
        } else {
            return redirect('/');
        }
    }

    public function destroy($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {

            if ($this->photo->_destroy($id) > 0) {
                echo (json_encode(['status' => 200]));
                return;
            } else {
                echo (json_encode(['status' => 500]));
                return;
            }
        } else {
            return redirect('/');
        }
    }
    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isAuth()) {
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
                return;
            } else {
                echo json_encode($data);
                return;
            }
        } else {
            return redirect('/');
        }
    }
}
