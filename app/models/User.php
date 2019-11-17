<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email=:email");
        $this->db->bindValue(':email', $email);
        $this->db->fetch();
        if ($this->db->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function login($email, $password)
    {
        $this->db->query("SELECT id,password FROM users WHERE email=:email");
        $this->db->bindValue(':email', $email);
        $user = $this->db->fetch();
        if (password_verify($password, $user->password)) {
            $_SESSION["id"] = $user->id;
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->db->query("UPDATE users SET password=:password WHERE id=:id");
        $this->db->bindValue(':password', $password);
        $this->db->bindValue(':id', $_SESSION["id"]);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }
}
