<?php
class Email
{
    private $db;

    public function __construct()
    {

        $this->db = new Database();
    }

    public function fetchAll()
    {
        if (isAuth()) {
            $this->db->query('SELECT * FROM emails ORDER BY id DESC');
            $this->db->execute();
            return $this->db->fetchAll();
        } else {
            return redirect('/');
        }
    }

    public function store($data)
    {
        $this->db->query('INSERT INTO emails(name, email, subject, message) VALUES(:name, :email, :subject, :message)');
        $this->db->bindValue(":name", $data['name']);
        $this->db->bindValue(":email", $data['email']);
        $this->db->bindValue(":subject", $data['subject']);
        $this->db->bindValue(":message", $data['message']);
        $this->db->execute();

        return $this->db->rowCount() > 0 ? ['status' => 200] : ['status' => 500];
    }
}
