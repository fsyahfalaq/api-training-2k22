<?php

class Model_user extends CI_Model {

    public function getByUsername($username)
    {
        $result = $this->db->get_where('user', ["username" => $username])->result();
        return $result;
    }

    public function insert($data)
    {
        $insert = $this->db->insert('user', $data);
        return $insert;
    }

    public function update($npm, $data)
    {
        $this->db->where('username', $npm);
        $update = $this->db->update('user', $data);
        return $update;
    }

    public function delete($npm)
    {
        $this->db->where('username', $npm);
        $delete = $this->db->delete('user');
        return $delete;
    }
}