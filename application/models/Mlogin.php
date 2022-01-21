<?php

class Mlogin extends CI_Model
{

    public function cek_login($email)
    {
        $q = $this->db->get_where('admin', array('email' => $email));
        return $q;
    }

    public function idTransaksi()
    {
        $cekId = $this->db->select('id')
            ->from('transaksi')
            ->get();

        $query = $cekId->last_row();

        if ($query) {
            $nextID = (int)$query->id + 1;
        } else {
            $nextID = (int)1;
        }

        return $nextID;
    }
}
