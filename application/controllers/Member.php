<?php

use chriskacerguis\RestServer\RestController;

class Member extends RestController {

    public function __construct()
    {
        parent::__construct();
        
        $token = $this->input->request_headers()['Authorization'];
        if(!$this->api->authToken($token)) {
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
        $delete = $this->Model_anggota->delete($npm);
        $delete = $this->Model_user->delete($npm);
        if($delete){
            $this->response([
                "message" => "Data Berhasil Dihapus"
            ], 201);
        }
    }

    public function update_post($npm)
    {
        $foto = "";
        if(isset($_FILES['foto'])){
            $foto = $_FILES['foto'];
        }

        if ($foto != "") {

            $config['upload_path']          = './assets/uploads/';
            $config['allowed_types']        = 'jpg|png';
            $config['max_size']             = 2048;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('foto')){
                $data['error'] = $this->upload->display_errors();
                $this->response($data, 500);
            } else {
                $uploaded_data = $this->upload->data();
                
                $data = [
                    "npm" => $this->post('npm'),
                    "nama" => $this->post('nama'),
                    "divisi" => $this->post('divisi'),
                    "lab" => $this->post('lab'),
                    "foto" => $uploaded_data['file_name'],
                    "last_update" => date("Y-m-d")
                ];

                $dataUser = [
                    "username" => $this->post('npm')
                ];

                $update = $this->Model_user->update($npm, $dataUser);
                $update = $this->Model_anggota->update($npm, $data);
                $this->response([
                    'status' => true,
                    'message' => 'Update Berhasil'
                ], 201);
            }

        } else {
            $data = [
                "npm" => $this->post('npm'),
                "nama" => $this->post('nama'),
                "divisi" => $this->post('divisi'),
                "lab" => $this->post('lab'),
                "last_update" => date("Y-m-d")
            ];

            $dataUser = [
                "username" => $this->post('npm')
            ];

            $update = $this->Model_user->update($npm, $dataUser);
            $update = $this->Model_anggota->update($npm, $data);
            $this->response([
                'status' => true,
                'message' => 'Update Berhasil'
            ], 201);
        }
        
    }
}