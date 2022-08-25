<?php

use chriskacerguis\RestServer\RestController;

class Member extends RestController
{
    public function __construct()
    {
        parent::__construct();

        $token = $this->input->request_headers()['Authorization'];
        if (!$this->api->authToken($token)) {
            $this->response([
                "status" => false,
                "message" => "Token Invalid"
            ]);
            die;
        }
        $this->load->model('Model_anggota');
        $this->load->model('Model_user');
    }

    public function all_get()
    {
        $result = $this->Model_anggota->getAll();

        $this->response($result, 200);
    }

    public function detail_get($npm)
    {
        $result = $this->Model_anggota->getByNPM($npm);

        $this->response($result, 200);
    }

    public function delete_delete($npm)
    {
        $deleteAnggota = $this->Model_anggota->delete($npm);
        $deleteUser = $this->Model_user->delete($npm);
        if ($deleteUser && $deleteAnggota) {
            $this->response([
                "message" => "Data Berhasil Dihapus"
            ], 201);
        } else {
            $this->response([
                "message" => "Data Gagal Dihapus"
            ]);
        }
    }

    public function update_post($npm)
    {
        $data = [
            "npm" => $this->post('npm'),
            "nama" => $this->post('nama'),
            "divisi" => $this->post('divisi'),
            "lab" => $this->post('lab'),
            "last_update" => date("Y-m-d")
        ];

        if (isset($_FILES['foto'])) {
            $config['upload_path']          = './assets/uploads/';
            $config['allowed_types']        = 'jpg|png';
            $config['max_size']             = 2048;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('foto')) {
                $data['error'] = $this->upload->display_errors();
                return $this->response($data, 500);
            } else {
                $uploaded_data = $this->upload->data();

                $data["foto"] = $uploaded_data['file_name'];
            }
        }
        $this->Model_anggota->update($npm, $data);

        $dataUser = ["username" => $this->post('npm')];
        $this->Model_user->update($npm, $dataUser);

        $this->response([
            'status' => true,
            'message' => 'Update Berhasil'
        ], 201);
    }
}
