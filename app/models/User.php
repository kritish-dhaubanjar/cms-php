<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll()
    {
        $this->db->query("SELECT id,name, email, role,created_at FROM users");
        return $this->db->fetchAll();
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
        $this->db->query("SELECT id,role,password FROM users WHERE email=:email");
        $this->db->bindValue(':email', $email);
        $user = $this->db->fetch();
        if (password_verify($password, $user->password)) {
            $_SESSION["id"] = $user->id;
            $_SESSION["role"] = $user->role;
            return true;
        } else {
            return false;
        }
    }

    // public function changePassword($password, $id)
    // {
    //     $password = password_hash($password, PASSWORD_DEFAULT);
    //     $this->db->query("UPDATE users SET password=:password WHERE id=:id");
    //     $this->db->bindValue(':password', $password);
    //     $this->db->bindValue(':id', $id);
    //     $this->db->execute();
    //     return ($this->db->rowCount() > 0);
    // }


    // By Admin //

    public function create($data)
    {
        $this->db->query("INSERT INTO users(name, email, password) VALUES(:name, :email, :password)");
        $this->db->bindValue(':name', $data['name']);
        $this->db->bindValue(':email', $data['email']);
        $this->db->bindValue(':password', password_hash($data["password"], PASSWORD_DEFAULT));

        $this->db->execute();
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function update($credentials)
    {
        if (!empty($credentials['password'])) {
            $this->db->query("UPDATE users SET name=:name, role=:role, password=:password WHERE id=:id");
            $this->db->bindValue(':password', password_hash($credentials['password'], PASSWORD_DEFAULT));
        } else {
            $this->db->query("UPDATE users SET name=:name, role=:role WHERE id=:id");
        }
        $this->db->bindValue(':name', $credentials['name']);
        $this->db->bindValue(':role', $credentials['role']);
        $this->db->bindValue(':id', $credentials['id']);
        $this->db->execute();
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function destroy($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bindValue(':id', $id);
        $this->db->execute();
        return $this->db->rowCount() > 0 ? array('status' => 200) : array('status' => 500);
    }
}
