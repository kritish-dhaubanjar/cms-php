<?php

class Dashboard extends Controller
{
    private $page;

    public function __construct()
    {
        apiMiddleware();
        $this->page = $this->model('page');
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
    }

    public function index()
    {
        echo json_encode($this->page->counter());
        return;
    }
}
