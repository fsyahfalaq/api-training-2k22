<?php

class Model_anggota extends CI_Model {

    public function getAll()
    {
        $result = $this->db->get('anggota')->result();
        return $result;
    }

    public function getByNPM($npm)
    {
        $result = $this->db->get_where('anggota', ["npm" => $npm])->result();
        return $result;
    }

    public function update($npm, $data)
    {
        $this->db->where('npm', $npm);
        $update = $this->db->update('anggota', $data);
        return $update;
    }

    public function insert($data)
    {
        $insert = $this->db->insert('anggota', $data);
        return $insert;
    }

    public function delete($npm)
    {
        $this->db->where('npm' , $npm);
        $delete = $this->db->delete('anggota');
        return $delete;
    }
}