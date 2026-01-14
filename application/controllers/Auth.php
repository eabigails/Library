<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Auth');
    }

    public function login() {
        if ($this->session->userdata('login')) {
            redirect('katalog');
        }
        $this->load->view('v_login');
    }

    public function proses_login() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        // Menggunakan fungsi cek_user yang diarahkan ke tabel user_login
        $user = $this->M_Auth->cek_user($username)->row_array();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $data_session = [
                    'login'    => TRUE,
                    'username' => $user['username'],
                    'role'     => $user['role'],
                    'ref_id'   => $user['ref_id']
                ];
                $this->session->set_userdata($data_session);

                if ($user['role'] == 'Admin') {
                    redirect('admin/dashboard'); 
                } else {
                    redirect('katalog');
                }
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth/login');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('auth/login');
        }
    }

    public function signup() {
        $this->load->view('v_signup');
    }

    public function proses_signup() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $nama     = $this->input->post('nama', TRUE);
        $alamat   = $this->input->post('alamat', TRUE);
        $no_telp  = $this->input->post('no_telp', TRUE);
        $email    = $this->input->post('email', TRUE);

        // 1. Cek Username di tabel user_login
        if ($this->M_Auth->cek_username($username) > 0) {
            $this->session->set_flashdata('error', 'Username sudah digunakan');
            redirect('auth/signup');
        }

        // 2. Generate ID Anggota Otomatis
        $last = $this->M_Auth->get_last_id();
        if ($last) {
            $lastId = (int) substr($last['id_anggota'], 3);
            $id_anggota = "AGT" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $id_anggota = "AGT001";
        }

        // 3. Siapkan Data untuk tabel 'anggota'
        $data_agt = [
            'id_anggota'    => $id_anggota,
            'nama'          => $nama,
            'alamat'        => $alamat,
            'no_telp'       => $no_telp,
            'email'         => $email,
            'tgl_bergabung' => date('Y-m-d')
        ];

        // 4. Siapkan Data untuk tabel 'user_login'
        $data_login = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => 'Anggota',
            'ref_id'   => $id_anggota // FK ke tabel anggota
        ];

        // 5. Eksekusi Simpan (Dual Table)
        if ($this->M_Auth->register($data_agt, $data_login)) {
            // Otomatis login setelah daftar
            $this->session->set_userdata([
                'login'    => TRUE,
                'username' => $username,
                'role'     => 'Anggota',
                'ref_id'   => $id_anggota
            ]);
            
            echo "<script>alert('Pendaftaran Berhasil!'); window.location='".base_url('katalog')."';</script>";
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar, terjadi gangguan sistem.');
            redirect('auth/signup');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}