<?php

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends RestController
{
    
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Model_anggota');
        $this->load->model('Model_user');
    }

    public function login_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        $checkUser = $this->Model_user->getByUsername($username);
        
        if($checkUser) {
            $checkPassword = password_verify($password, $checkUser[0]->password);

            if($checkPassword) {
                $checkAnggota = $this->Model_anggota->getByNPM($username);

                $data = [
                    "npm" => $username,
                    "nama" => $checkAnggota[0]->nama,
                    "role" => $checkUser[0]->role,
                    "divisi" => $checkAnggota[0]->divisi,
                    "lab" => $checkAnggota[0]->lab,
                    "foto" => $checkAnggota[0]->foto
                ];
                $this->response($this->api->getToken($data),200);
            } else {
                $this->response([
                    "status" => 404,
                    "message" => "Username or Password Wrong !!"
                ]);
            }
        }
    }

    public function register_post()
    {
        $foto = "";
        if(isset($_FILES['foto'])){
            $foto = $_FILES['foto'];
        }

        if($foto != ''):
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
                "created_at" => date("Y-m-d"),
                "last_update" => date("Y-m-d")
            ];

            $insert = $this->Model_anggota->insert($data);
            if(!$insert) {
                $this->response([
                    "status" => false,
                    "message" => "Failed insert data to anggota table !!"
                ], 500);
                die;
            }
        }
        else:
            $data = [
                "npm" => $this->post('npm'),
                "nama" => $this->post('nama'),
                "divisi" => $this->post('divisi'),
                "lab" => $this->post('lab'),
                "created_at" => date("Y-m-d"),
                "last_update" => date("Y-m-d")
            ];

            $insert = $this->Model_anggota->insert($data);
            if(!$insert) {
                $this->response([
                    "status" => false,
                    "message" => "Failed insert data to anggota table !!"
                ], 500);
                die;
            }
        endif;

        $data = [
            "username" => $this->post('npm'),
            "password" => password_hash($this->post('password'), PASSWORD_DEFAULT),
            "role" => $this->post('role'),
            "created_at" => date("Y-m-d"),
            "last_update" => date("Y-m-d")
        ];

        $insert = $this->Model_user->insert($data);
        if(!$insert) {
            $this->response([
                "status" => false,
                "message" => "Failed insert data to user table !!"
            ], 500);
            die;
        }

        $this->response("Data successfully insert!!", 200);
    }
    
}
