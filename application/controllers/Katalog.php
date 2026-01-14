<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Katalog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Katalog'); 
        $this->load->helper('url');
    }

    public function index() {
        $q = $this->input->get('q');
        $kat = $this->input->get('kategori');

        $data['kategori_list'] = $this->M_Katalog->get_kategori();
        $data['buku_list'] = $this->M_Katalog->get_buku($q, $kat);
        
        // Memasukkan model ke data agar bisa dipanggil di view untuk cek ketersediaan
        $data['M_Katalog'] = $this->M_Katalog;

        $this->load->view('v_katalog', $data);
    }
}