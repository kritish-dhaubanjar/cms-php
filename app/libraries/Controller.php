<?php

class Controller
{
    public function model($model)
    {
        require("./../app/models/" . ucwords($model) . ".php");
        return new $model;
    }

    public function view($view, $data = [])
    {
        require("./../app/views/" . ucwords($view) . ".php");
    }
}
