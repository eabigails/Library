<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek login & role
        if ($this->session->userdata('role') != 'Anggota') { 
            redirect('auth'); 
        }
        $this->load->model('M_Peminjaman');
        $this->load->library('user_agent'); // Penting untuk referrer
    }

    public function proses_pinjam($id_koleksi) {
        $id_anggota = $this->session->userdata('ref_id');
        $referrer = $this->agent->referrer() ? $this->agent->referrer() : base_url('katalog');

        // 1. Cek apakah user sedang meminjam buku yang sama dan belum dikembalikan
        $this->db->select('p.id_peminjaman');
        $this->db->from('peminjaman p');
        $this->db->join('detail_peminjaman d', 'p.id_peminjaman = d.id_peminjaman');
        $this->db->where('p.id_anggota', $id_anggota);
        $this->db->where('d.id_koleksi', $id_koleksi);
        $this->db->where('p.status', 'Dipinjam');
        $cek_saya = $this->db->get()->num_rows();

        if ($cek_saya > 0) {
            $this->session->set_flashdata('error', 'Anda masih meminjam buku ini.');
            redirect($referrer);
        }

        // 2. Cek apakah buku sedang dipinjam orang lain
        $cek_orang = $this->M_Peminjaman->cek_status_buku($id_koleksi)->num_rows();
        
        if ($cek_orang > 0) {
            // Cek apakah sudah ada di wishlist untuk mencegah duplikat
            $cek_wishlist = $this->db->get_where('wishlist', [
                'id_anggota' => $id_anggota, 
                'id_koleksi' => $id_koleksi
            ])->num_rows();
            
            if ($cek_wishlist == 0) {
                $data_wish = [
                    'id_anggota' => $id_anggota,
                    'id_koleksi' => $id_koleksi,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('wishlist', $data_wish);
                $this->session->set_flashdata('success', 'Buku sedang dipinjam. Otomatis masuk ke wishlist!');
            } else {
                $this->session->set_flashdata('error', 'Buku sedang dipinjam & sudah ada di wishlist Anda.');
            }
            redirect($referrer);
            return;
        }

        // 3. Jika tersedia, proses peminjaman baru
        $id_peminjaman = $this->M_Peminjaman->generate_id_pinjam();
        $data_pinjam = [
            'id_peminjaman' => $id_peminjaman,
            'id_anggota'    => $id_anggota,
            'tgl_pinjam'    => date('Y-m-d'),
            'tgl_kembali'   => date('Y-m-d', strtotime('+7 days')),
            'status'        => 'Dipinjam'
        ];

        if ($this->M_Peminjaman->simpan_peminjaman($data_pinjam, $id_koleksi)) {
            $this->session->set_flashdata('success', 'Buku berhasil dipinjam!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses peminjaman.');
        }
        
        redirect($referrer);
    }
}