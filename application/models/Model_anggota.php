<?php

class Model_anggota extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('anggota')->result();
    }

    public function getByNPM($npm)
    {
        return $this->db->get_where('anggota', ["npm" => $npm])->result();
    }

    public function update($npm, $data)
    {
        $this->db->where('npm', $npm);
        return $this->db->update('anggota', $data);
    }

    public function insert($data)
    {
        return $this->db->insert('anggota', $data);
    }

    public function delete($npm)
    {
        $this->db->where('npm', $npm);
        return $this->db->delete('anggota');
    }
}
