<?php

class Model_user extends CI_Model {

    public function getByUsername($username)
    {
        return $this->db->get_where('user', ["username" => $username])->result();
    }

    public function insert($data)
    {
        return $this->db->insert('user', $data);
    }

    public function update($npm, $data)
    {
        $this->db->where('username', $npm);
        return $this->db->update('user', $data);
    }

    public function delete($npm)
    {
        $this->db->where('username', $npm);
        return $this->db->delete('user');
    }
}
