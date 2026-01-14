<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat Model dan Library
        $this->load->model('M_Admin');
        
        // Proteksi: Jika belum login atau bukan Admin, lempar ke login
        if (!$this->session->userdata('login') || $this->session->userdata('role') != 'Admin') {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda bukan admin.');
            redirect('auth/login');
        }
    }

    public function dashboard() {
        $data['stats'] = $this->M_Admin->get_stats();
        $data['recent'] = $this->M_Admin->get_recent_activities();
        $data['username'] = $this->session->userdata('username');

        $this->load->view('admin/v_dashboard', $data);
    }

    public function peminjaman() {
    $this->load->model('M_Peminjaman');
    $data['pinjaman'] = $this->M_Peminjaman->get_all_peminjaman();
    $data['username'] = $this->session->userdata('username'); 
    $data['anggota'] = $this->db->get('anggota')->result_array();
    $this->load->view('admin/v_peminjaman', $data); 
}

    // Form Tambah
    public function peminjaman_tambah() {
        $this->load->model('M_Peminjaman');
        $data['newId'] = $this->M_Peminjaman->generate_id();
        $data['anggota'] = $this->db->order_by('nama', 'ASC')->get('anggota')->result_array();
        
        
        $data['buku'] = $this->db->query("SELECT * FROM koleksi WHERE id_koleksi NOT IN (
            SELECT dp.id_koleksi FROM detail_peminjaman dp 
            JOIN peminjaman p ON dp.id_peminjaman = p.id_peminjaman WHERE p.status = 'Dipinjam'
        ) ORDER BY judul ASC")->result_array();

        $this->load->view('admin/v_peminjaman_tambah', $data);
    }

    // Proses Simpan
    public function proses_peminjaman() {
        $this->load->model('M_Peminjaman');
        $id_pjm = $this->M_Peminjaman->generate_id();
        
        $data_pjm = [
            'id_peminjaman' => $id_pjm,
            'id_anggota'    => $this->input->post('id_anggota'),
            'id_karyawan'   => ($this->session->userdata('role') == 'Admin') ? null : $this->session->userdata('ref_id'),
            'tgl_pinjam'    => date('Y-m-d'),
            'tgl_kembali'   => date('Y-m-d', strtotime('+7 days')),
            'status'        => 'Dipinjam'
        ];

        $data_detail = [
            'id_peminjaman' => $id_pjm,
            'id_koleksi'    => $this->input->post('id_koleksi')
        ];

        if ($this->M_Peminjaman->simpan($data_pjm, $data_detail)) {
            $this->session->set_flashdata('msg', 'Peminjaman berhasil dicatat!');
            redirect('admin/peminjaman');
        }
    }

    // Selesai Pinjam
    public function peminjaman_selesai($id) {
    // Ambil ref_id dari session
    $ref_id = $this->session->userdata('ref_id');

    // PROTEKSI: Cek apakah ref_id ini ada di tabel karyawan
    $cek_karyawan = $this->db->get_where('karyawan', ['id_karyawan' => $ref_id])->row();

    if ($cek_karyawan) {
        $id_petugas = $ref_id;
    } else {
        // Jika tidak ada (misal login pakai akun anggota tapi masuk link admin)
        // Set ke NULL atau ambil ID karyawan default dari database
        $karyawan_default = $this->db->get('karyawan')->row();
        $id_petugas = ($karyawan_default) ? $karyawan_default->id_karyawan : null;
    }

    $this->db->where('id_peminjaman', $id);
    $this->db->update('peminjaman', [
        'status' => 'Kembali',
        'id_karyawan' => $id_petugas // Menggunakan ID yang valid dari tabel karyawan
    ]);

    $this->session->set_flashdata('msg', 'Buku telah kembali!');
    redirect('admin/peminjaman');
}
    // Daftar Buku
    public function buku() {
        $this->load->model('M_Buku');
        $data['buku'] = $this->M_Buku->get_all_buku();
        $this->load->view('admin/v_buku', $data);
    }

    // Form Tambah Buku
    public function buku_tambah() {
        $this->load->model('M_Buku');
        $data['newId'] = $this->M_Buku->generate_id();
        $data['kategori'] = $this->db->get('kategori')->result_array();
        $this->load->view('admin/v_buku_tambah', $data);
    }

    // Proses Simpan Buku
    public function proses_tambah_buku() {
        $this->load->model('M_Buku');
        $id_baru = $this->M_Buku->generate_id();
        
        // Konfigurasi Upload
        $config['upload_path']   = './uploads/koleksi/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = date('dmYHis') . '_' . $id_baru;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            $file_data = $this->upload->data();
            $gambar = $file_data['file_name'];
        } else {
            $gambar = 'default.jpg';
        }

        $data = [
            'id_koleksi'    => $id_baru,
            'judul'         => $this->input->post('judul'),
            'penulis'       => $this->input->post('penulis'),
            'penerbit'      => $this->input->post('penerbit'),
            'tahun_terbit'  => $this->input->post('tahun_terbit'),
            'id_kategori'   => $this->input->post('id_kategori'),
            'gambar'        => $gambar
        ];

        $this->db->insert('koleksi', $data);
        $this->session->set_flashdata('msg', 'Buku berhasil ditambahkan!');
        redirect('admin/buku');
    }

    // Hapus Buku
    public function buku_hapus($id) {
        $this->load->model('M_Buku');
        if ($this->M_Buku->hapus_buku($id)) {
            $this->session->set_flashdata('msg', 'Buku berhasil dihapus!');
        }
        redirect('admin/buku');
    }
    public function anggota() {
        $this->load->model('M_Anggota');
        $data['anggota'] = $this->M_Anggota->get_all_anggota();
        $this->load->view('admin/v_anggota', $data);
    }

    public function anggota_hapus($id) {
        $this->load->model('M_Anggota');
        // Kita beri error handling jika gagal (misal karena constraint database)
        if ($this->M_Anggota->hapus_anggota($id)) {
            $this->session->set_flashdata('msg', 'Data anggota dan akun berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus! Anggota mungkin masih memiliki riwayat pinjam.');
        }
        redirect('admin/anggota');
    }
}