<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi: Hanya Anggota yang bisa akses
        if ($this->session->userdata('role') != 'Anggota') {
            redirect('auth');
        }
        $this->load->model('M_Peminjaman');
    }

    public function riwayat() {
        // Ambil ID Anggota dari session
        $id_anggota = $this->session->userdata('ref_id');
        
        $data['username'] = $this->session->userdata('username');
        $data['riwayat'] = $this->M_Peminjaman->get_riwayat_by_anggota($id_anggota);
        
        $this->load->view('anggota/v_riwayat', $data);
    }

    public function wishlist() {
    $id_anggota = $this->session->userdata('ref_id'); 
    
    // Cek apakah ID muncul. Jika NULL, berarti login session salah/belum diset.
    // var_dump($id_anggota); die(); 

    $this->load->model('M_Wishlist');
    $this->load->model('M_Katalog');
    
    $data['wishlist'] = $this->M_Wishlist->get_wishlist_by_anggota($id_anggota);
    $data['M_Katalog'] = $this->M_Katalog;

    $this->load->view('anggota/v_wishlist', $data);
}

    public function hapus_wishlist($id) {
        $this->load->model('M_Wishlist');
        $id_anggota = $this->session->userdata('ref_id');
        
        if ($this->M_Wishlist->hapus_wishlist($id, $id_anggota)) {
            $this->session->set_flashdata('success', 'Berhasil dihapus dari wishlist.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect($this->agent->referrer());
    }
}