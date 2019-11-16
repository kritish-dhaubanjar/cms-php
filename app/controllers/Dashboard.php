<?php

class Pages extends Controller
{
    private $page;

    public function __construct()
    {
        $this->page = $this->model('page');
    }

    public function index()
    {

        $this->page->counter();
    }
}
