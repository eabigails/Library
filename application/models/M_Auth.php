<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model {

    // Digunakan untuk proses login
    public function cek_user($username) {
        return $this->db->get_where('user_login', ['username' => $username]);
    }

    // Digunakan untuk validasi signup
    public function cek_username($username) {
        return $this->db->get_where('user_login', ['username' => $username])->num_rows();
    }

    // Ambil ID Anggota terakhir untuk auto-increment manual (AGTxxx)
    public function get_last_id() {
        $this->db->order_by('id_anggota', 'DESC');
        return $this->db->get('anggota', 1)->row_array();
    }

    // Proses Register masuk ke dua tabel dalam satu transaksi
    public function register($data_agt, $data_login) {
        $this->db->trans_start(); // Memulai transaksi database
        
        $this->db->insert('anggota', $data_agt);
        $this->db->insert('user_login', $data_login);
        
        $this->db->trans_complete(); // Selesai transaksi

        return $this->db->trans_status(); // Return TRUE jika keduanya berhasil
    }
}