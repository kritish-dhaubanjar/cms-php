<?php

function redirect($url)
{
    header("location:" . URL . $url);
}

function getFilename($name)
{
    return str_replace(" ", "_", pathinfo($name, PATHINFO_FILENAME));
}

function getExtension($name)
{
    return pathinfo($name, PATHINFO_EXTENSION);
}
