<?php
require_once("./../app/controllers/Photos.php");

class Covers extends Photos
{
    public function __construct()
    {
        parent::__construct('Cover');
    }
}
