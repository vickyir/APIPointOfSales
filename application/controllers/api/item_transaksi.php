<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Item_transaksi extends RestController
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
            $item_transaksi = $this->db->get('item_transaksi')->result();
        } else {
            $item_transaksi = $this->db->get_where('item_transaksi', array('id' => $id))->row_object();
        }

        if ($item_transaksi) {
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => [
                    "item_transaksi" => $item_transaksi
                ]
            );

            $this->response($data_json, RestController::HTTP_OK);
        } else {
            $data_json = array(
                "success" => false,
                "message" => "data not found!",
                "data" => [
                    "transaksi" => null
                ]
            );

            $this->response($data_json, RestController::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $this->form_validation->set_rules('transaksi_id', 'transaksi_id', 'required');
        $this->form_validation->set_rules('product_id', 'product_id', 'required');
        $this->form_validation->set_rules('qty', 'qty', 'required');
        $this->form_validation->set_rules('harga_saat_ini', 'harga_saat_ini', 'required');
        $this->form_validation->set_rules('sub_harga', 'sub_harga', 'required');

        if ($this->form_validation->run() == false) {
            echo form_error('transaksi_id');
            echo form_error('product_id');
            echo form_error('qty');
            echo form_error('harga_saat_ini');
            echo form_error('sub_harga');
            exit();
        }
        $data_post = array(
            "transaksi_id" => $this->post('transaksi_id'),
            "product_id" => $this->post('product_id'),
            "qty" => $this->post('qty'),
            "harga_saat_ini" => $this->post('harga_saat_ini'),
            "sub_harga" => $this->post('sub_harga')
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
            $this->response($data_json, RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $transaksi_id = $this->put('transaksi_id');
        $product_id = $this->put('product_id');
        $qty = $this->put('qty');
        $harga = $this->put('harga_saat_ini');
        $sub_harga = $this->put('sub_harga');
        $validationMessage = [];


        if ($id == '') {
            array_push($validationMessage, 'id wajib diisi');
        }
        if ($transaksi_id == '') {
            array_push($validationMessage, 'admin_id wajib diisi');
        }
        if ($product_id == '') {
            array_push($validationMessage, 'Nama produk wajib diisi');
        }
        if ($qty == '') {
            array_push($validationMessage, 'qty wajib diisi');
        }
        if ($harga == '') {
            array_push($validationMessage, 'harga saat ini wajib diisi');
        }
        if ($sub_harga == '') {
            array_push($validationMessage, 'sub harga wajib diisi');
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
            "transaksi_id" => $this->put('transaksi_id'),
            "product_id" => $this->put('product_id'),
            "qty" => $this->put('qty'),
            "harga_saat_ini" => $this->put('harga_saat_ini'),
            "sub_harga" => $this->put('sub_harga')
        );
        $this->db->where('id', $id);
        $update = $this->db->update('item_transaksi', $data_json);

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
        $data = $this->db->get_where('item_transaksi', array('id' => $id))->row_object();
        if ($data) {
            $this->db->where('id', $id);
            $this->db->delete('item_transaksi');
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
