<?php

session_start();

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

function isAuth()
{
    if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
        return true;
    }
    return false;
}

function isAdmin()
{
    return (isset($_SESSION["role"]) && !empty($_SESSION["role"]) && $_SESSION["role"] == "admin");
}
