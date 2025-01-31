<?php

class Job extends Model
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function store($data, $photoModel)
    {
        $this->db->query("INSERT INTO jobs(title, lastDate, offeredSalary, careerLevel, location, industry, experience, content) VALUES(:title, :lastDate,:offeredSalary, :careerLevel, :location, :industry, :experience, :content)");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':lastDate', $data["lastDate"]);
        $this->db->bindValue(':offeredSalary', $data["offeredSalary"]);
        $this->db->bindValue(':careerLevel', $data["careerLevel"]);
        $this->db->bindValue(':location', $data["location"]);
        $this->db->bindValue(':industry', $data["industry"]);
        $this->db->bindValue(':experience', $data["experience"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->execute();

        $id = $this->db->lastInsertId();
        $rowCount = $this->db->rowCount();
        $photoModel->store($this->class, $id, null);
        return $rowCount > 0 ? array('status' => 200) : array('status' => 500);
    }

    public function update($id, $data, $photoModel)
    {
        $this->db->query("UPDATE jobs SET title=:title, lastDate=:lastDate, offeredSalary=:offeredSalary, careerLevel=:careerLevel, location=:location, industry=:industry, experience=:experience, content=:content WHERE id=:id");
        $this->db->bindValue(':title', $data["title"]);
        $this->db->bindValue(':lastDate', $data["lastDate"]);
        $this->db->bindValue(':offeredSalary', $data["offeredSalary"]);
        $this->db->bindValue(':careerLevel', $data["careerLevel"]);
        $this->db->bindValue(':location', $data["location"]);
        $this->db->bindValue(':industry', $data["industry"]);
        $this->db->bindValue(':experience', $data["experience"]);
        $this->db->bindValue(':content', $data["content"]);
        $this->db->bindValue(':id', $id);

        $this->db->execute();

        $photoModel->destroy($this->class, $id, $data["items"]);
        $photoModel->store($this->class, $id, null);
        return array('status' => 200);
    }

    public function filter($data)
    {
        $limit = 10;

        $this->db->query("SELECT count(id) AS count FROM jobs WHERE title LIKE :title AND location LIKE :location AND industry LIKE :industry AND careerLevel LIKE :careerLevel AND offeredSalary LIKE :offeredSalary AND experience LIKE :experience");

        $this->db->bindValue(':title', '%' . $data['keyword'] . '%');
        $this->db->bindValue(':location', '%' . $data['location'] . '%');
        $this->db->bindValue(':industry', '%' . $data['industry'] . '%');
        $this->db->bindValue(':careerLevel', '%' . $data['careerLevel'] . '%');
        $this->db->bindValue(':offeredSalary', '%' . $data['offeredSalary'] . '%');
        $this->db->bindValue(':experience', '%' . $data['experience'] . '%');

        $total = $this->db->fetch();

        $count = $total->count;

        $this->db->query("SELECT * FROM jobs WHERE title LIKE :title AND location LIKE :location AND industry LIKE :industry AND careerLevel LIKE :careerLevel AND offeredSalary LIKE :offeredSalary AND experience LIKE :experience ORDER BY id DESC LIMIT :offset, :limit");
        $this->db->bindValue(':limit', $limit);
        $this->db->bindValue(':offset', ($data['page'] - 1) * $limit);
        $this->db->bindValue(':title', '%' . $data['keyword'] . '%');
        $this->db->bindValue(':location', '%' . $data['location'] . '%');
        $this->db->bindValue(':industry', '%' . $data['industry'] . '%');
        $this->db->bindValue(':careerLevel', '%' . $data['careerLevel'] . '%');
        $this->db->bindValue(':offeredSalary', '%' . $data['offeredSalary'] . '%');
        $this->db->bindValue(':experience', '%' . $data['experience'] . '%');
        $result = $this->db->fetchAll();

        $pages = ceil($count / $limit);

        $temp["data"] = $result;
        $temp["meta"]["total_pages"] = $pages;
        $temp["meta"]["prev_page"] = (int) $data['page'] == 1 ? null : $data['page'] - 1;
        $temp["meta"]["current_page"] = (int) $data['page'];
        $temp["meta"]["next_page"] = (int) $data['page'] == $pages ? null : $data['page'] + 1;
        return $temp;

        // return $result;
    }
}
