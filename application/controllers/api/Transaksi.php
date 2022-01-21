<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi extends RestController
{

    function __construct($config = 'rest')
    {
        // Construct the parent class
        parent::__construct($config);
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id == '') {
            $transaksi = $this->db->get('transaksi')->result();
        } else {
            $transaksi = $this->db->get_where('transaksi', array('id' => $id))->row_object();
        }

        if ($transaksi) {
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => [
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

    public function index_post()
    {
        $this->form_validation->set_rules('admin_id', 'admin_id', 'required');
        $this->form_validation->set_rules('date', 'date', 'required');
        $this->form_validation->set_rules('total', 'total', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('admin_id');
            echo form_error('tanggal');
            echo form_error('total');
            exit();
        }
        $data_post = array(
            "admin_id" => $this->post('admin_id'),
            "date" => $this->post('date'),
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
                "data" => null
            );
            $this->response($data_json, RestController::HTTP_OK);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $admin_id = $this->put('admin_id');
        $date = $this->put('date');
        $total = $this->put('total');
        $validationMessage = [];


        if ($id == '') {
            array_push($validationMessage, 'id wajib diisi');
        }
        if ($admin_id == '') {
            array_push($validationMessage, 'admin_id wajib diisi');
        }
        if ($date == '') {
            array_push($validationMessage, 'Nama produk wajib diisi');
        }
        if ($total == '') {
            array_push($validationMessage, 'harga wajib diisi');
        }


        if (count($validationMessage) > 0) {
            $data_json = array(
                "success" => false,
                "message" => "data not valid !",
                "data" => $validationMessage
            );
            $this->response($data_json, RestController::HTTP_BAD_REQUEST);
            $this->output->_display();
            exit();
        }

        $data_json = array(
            "admin_id" => $this->put('admin_id'),
            "date" => $this->put('date'),
            "total" => $this->put('total')
        );
        $this->db->where('id', $id);
        $update = $this->db->update('transaksi', $data_json);

        if ($update) {
            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => false,
                "message" => "data not valid !"
            );
            $this->response($data_json, RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');
        $data = $this->db->get_where('transaksi', array('id' => $id))->row_object();
        if ($data) {
            $this->db->where('id', $id);
            $this->db->delete('transaksi');
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
            $this->response($data_json, RestController::HTTP_BAD_REQUEST);
        }
    }
}
