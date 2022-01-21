<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/RestController.php';

use chriskacerguis\RestServer\RestController;

require APPPATH . '/libraries/firebase/JWT/JWT.php';

use \Firebase\JWT\JWT;

class Admin extends RestController
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

        $id = $this->get('id');
        if ($id == '') {
            $admin = $this->db->get('admin')->result();
        } else {
            $admin = $this->db->get_where('admin', array('id' => $id))->row_object();
        }

        if ($admin) {
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => $admin
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
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required');
        $this->form_validation->set_rules('nama', 'nama', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('id');
            echo form_error('email');
            echo form_error('password');
            echo form_error('nama');
            exit();
        }

        $data_post = array(
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'nama' => $this->post('nama')
        );

        $insert = $this->db->insert('admin', $data_post);
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
        $email = $this->put('email');
        $password = $this->put('password');
        $nama = $this->put('nama');
        $validationMessage = [];


        if ($id == '') {
            array_push($validationMessage, 'id wajib diisi');
        }
        if ($email == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($validationMessage, 'email tidak valid');
        }
        if ($password == '') {
            array_push($validationMessage, 'password produk wajib diisi');
        }
        if ($nama == '') {
            array_push($validationMessage, 'nama wajib diisi');
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
            'email' => $this->put('email'),
            'password' => password_hash($this->put('password'), PASSWORD_DEFAULT),
            'nama' => $this->put('nama')
        );
        $this->db->where('id', $id);
        $update = $this->db->update('admin', $data_json);

        if ($update) {
            $data_put = array(
                "success" => true,
                "message" => "data valid",
                "data" => $data_json
            );
            $this->response($data_put, RestController::HTTP_OK);
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
        $data = $this->db->get_where('admin', array('id' => $id))->row_object();
        if ($data) {
            $this->db->where('id', $id);
            $this->db->delete('admin');
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

    public function produk_get()
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

    public function produk_post()
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

    public function produk_delete()
    {
        $this->cekToken();
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

    public function produk_put()
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

    public function transaksi_get()
    {
        $this->cekToken();
        $id = $this->get('id');
        if ($id == '') {
            $transaksi = $this->db->get('transaksi')->result();
        } else {
            $transaksi = $this->db->get_where('transaksi', array('id' => $id))->row_object();
        }

        $total = 0;
        foreach ($transaksi as $item) {
            $total = $total + $item->total;
        }

        if ($transaksi) {
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => [
                    "total" => $total,
                    "transaksi" => $transaksi
                ]
            );

            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => false,
                "message" => "data not found!",

            );

            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function transaksi_post()
    {
        $this->cekToken();
        $id_transaksi = $this->Mlogin->idTransaksi();
        $this->form_validation->set_rules('admin_id', 'admin_id', 'required');
        $this->form_validation->set_rules('total', 'total', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('admin_id');
            echo form_error('total');
            exit();
        }
        $data_post = array(
            "id" => $id_transaksi,
            "admin_id" => $this->post('admin_id'),
            "tanggal" => date('Y-m-d h:i:s'),
            "total" => $this->post('total')
        );


        $insert = $this->db->insert('transaksi', $data_post);
        if ($insert) {
            $data_json = array(
                "success" => true,
                "message" => "data valid",
                "data" => array(
                    "transaksi" => $data_post
                )
            );
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => true,
                "message" => "data not valid",

            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function itemTransaksi_post()
    {
        $this->cekToken();
        $this->form_validation->set_rules('transaksi_id', 'transaksi_id', 'required');
        $this->form_validation->set_rules('product_id', 'product_id', 'required');
        $this->form_validation->set_rules('qty', 'qty', 'required');
        $this->form_validation->set_rules('harga_saat_transaksi', 'harga_saat_transaksi', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('transaksi_id');
            echo form_error('product_id');
            echo form_error('qty');
            echo form_error('harga_saat_transaksi');
            exit();
        }
        $data_post = array(
            "transaksi_id" => $this->post('transaksi_id'),
            "product_id" => $this->post('product_id'),
            "qty" => $this->post('qty'),
            "harga_saat_transaksi" => $this->post('harga_saat_transaksi'),
            "sub_total" => (string)($this->post('qty') * $this->post('harga_saat_transaksi'))
        );
        $insert = $this->db->insert('item_transaksi', $data_post);
        $produk = $this->db->get_where('product', array('id' => $this->post('product_id')))->row_object();
        $stok_lama = $produk->stok;
        $stok_baru = $stok_lama - $this->post('qty');
        if ($stok_baru < 0) {
            echo 'Stok tidak mencukupi';
            exit();
        } else {
            $this->db->set('stok', $stok_baru);
            $this->db->where('id', $this->post('product_id'));
            $this->db->update('product');
        }
        if ($insert) {
            $data_json = array(
                "success" => true,
                "message" => "data valid",
                "data" => array(
                    "item_transaksi" => $data_post
                )
            );
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => true,
                "message" => "data not valid",
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
                    "success" => false,
                    "message" => "Password salah",
                    "error_code" => 1308,
                    "data" => null
                );
                $this->response($data_json, RestController::HTTP_OK);
            }
        } else {
            $data_json = array(
                "success" => false,
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
                "success" => false,
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
