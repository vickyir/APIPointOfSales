<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . '/libraries/firebase/JWT/JWT.php';

use \Firebase\JWT\JWT;

class Produk extends RestController
{
    private $secret_key = "asd6a78rqjkhcdal903hjdfhjkadhd2673manjz";
    function __construct($config = 'rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Mlogin');
    }

    public function index_get()
    {
        $this->cekToken();
        $id = $this->get('id');
        if ($id == '') {
            $produk = $this->db->get('product')->result();
        } else {
            $produk = $this->db->get_where('product', array('id' => $id))->row_object();
        }

        if ($produk) {
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => array(
                    "produk" => $produk
                )
            );
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => true,
                "message" => "data not found",
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function index_post()
    {
        $this->cekToken();
        $this->form_validation->set_rules('admin_id', 'admin_id', 'required');
        $this->form_validation->set_rules('product_name', 'product_name', 'required');
        $this->form_validation->set_rules('harga', 'harga', 'required');
        $this->form_validation->set_rules('stok', 'stok', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('admin_id');
            echo form_error('product_name');
            echo form_error('harga');
            echo form_error('stok');
            exit();
        }

        $data_post = array(
            'admin_id' => $this->post('admin_id'),
            'product_name' => $this->post('product_name'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok')
        );

        $insert = $this->db->insert('product', $data_post);
        if ($insert) {
            $data_json = array(
                "success" => true,
                "message" => "data valid",
                "data" => $data_post
            );
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => true,
                "message" => "data not valid!",
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $admin_id = $this->put('admin_id');
        $nama_produk = $this->put('product_name');
        $harga = $this->put('harga');
        $stok = $this->put('stok');
        $validationMessage = [];


        if ($id == '') {
            array_push($validationMessage, 'id wajib diisi');
        }
        if ($admin_id == '') {
            array_push($validationMessage, 'admin_id wajib diisi');
        }
        if ($nama_produk == '') {
            array_push($validationMessage, 'Nama produk wajib diisi');
        }
        if ($harga == '') {
            array_push($validationMessage, 'harga wajib diisi');
        }
        if ($stok == '') {
            array_push($validationMessage, 'Stok wajib diisi');
        }

        if (count($validationMessage) > 0) {
            $data_json = array(
                "success" => false,
                "message" => "data not valid !",
                "data" => $validationMessage
            );
            $this->response($data_json, RestController::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json = array(
            'admin_id' => $this->put('admin_id'),
            'product_name' => $this->put('product_name'),
            'harga' => $this->put('harga'),
            'stok' => $this->put('stok')
        );
        $this->db->where('id', $id);
        $update = $this->db->update('product', $data_json);

        if ($update) {
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => false,
                "message" => "data not valid !"
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');
        $data = $this->db->get_where('product', array('id' => $id))->row_object();
        if ($data) {
            $this->db->where('id', $id);
            $this->db->delete('product');
            $data_json = array(
                "success" => true,
                "message" => "data valid",
                "data" => $data
            );
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => false,
                "message" => "data not valid !"
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function login_post()
    {
        $e = $this->input->post('email');
        $p = $this->input->post('password');
        $email = $this->Mlogin->cek_login($e)->row_array();
        if ($email) {
            if (password_verify($p, $email['password'])) {
                $date = new DateTime();
                $payload["id"] = $email["id"];
                $payload["email"] = $email["email"];
                $payload["iat"] = $date->getTimestamp();
                $payload["exp"] = $date->getTimestamp() + 3600;
                $data_json = array(
                    "success" => true,
                    "message" => "Otentikasi valid",
                    "data" => array(
                        "admin" => $email,
                        "token" => JWT::encode($payload, $this->secret_key)
                    )
                );
                $this->response($data_json, RestController::HTTP_OK);
            } else {

                $data_json = array(
                    "success" => FALSE,
                    "message" => "Password salah",
                    "error_code" => 1308,
                    "data" => null
                );
                $this->response($data_json, RestController::HTTP_OK);
            }
        } else {
            $data_json = array(
                "success" => FALSE,
                "message" => "Email tidak terdaftar",
                "error_code" => 1204,
                "data" => null
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function cekToken()
    {
        try {
            $token = $this->input->get_request_header('Authorization');

            if (!empty($token)) {
                $token = explode(' ', $token)[1];
            }

            $token_decode = JWT::decode($token, $this->secret_key, array('HS256'));
            // print_r($token_decode);
            // exit();
        } catch (Exception $e) {
            $data_json = array(
                "success" => FALSE,
                "message" => "Token not valid",
                "error_code" => 1204,
                "data" => null
            );
            $this->response($data_json, RestController::HTTP_OK);
            $this->output->_display();
            exit();
        }
    }
}
