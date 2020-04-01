<?php
require_once("./../app/controllers/Photos.php");

class Carousels extends Photos
{
    public function __construct()
    {
        parent::__construct('Carousel');
    }
}
