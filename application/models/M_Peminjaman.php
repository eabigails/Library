<?php
class M_Peminjaman extends CI_Model {

    public function get_all_peminjaman() {
        $this->db->select('p.*, a.nama as nama_anggota, k.judul');
        $this->db->from('peminjaman p');
        $this->db->join('anggota a', 'p.id_anggota = a.id_anggota', 'left'); // Menggunakan left join agar data tetap muncul meski anggota bermasalah
        $this->db->join('detail_peminjaman dp', 'p.id_peminjaman = dp.id_peminjaman');
        $this->db->join('koleksi k', 'dp.id_koleksi = k.id_koleksi');
        
        // Custom Sort: Terlambat -> Dipinjam -> Selesai
        $order = "CASE 
                    WHEN p.status = 'Dipinjam' AND p.tgl_kembali < CURDATE() THEN 1 
                    WHEN p.status = 'Dipinjam' THEN 2 
                    ELSE 3 
                  END ASC, p.tgl_pinjam DESC";
        $this->db->order_by($order);
        
        return $this->db->get()->result_array();
    }

    public function generate_id() {
        $last = $this->db->select('id_peminjaman')->order_by('id_peminjaman', 'DESC')->limit(1)->get('peminjaman')->row_array();
        if ($last) {
            $lastId = (int) substr($last['id_peminjaman'], 2);
            return "PJ" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
        }
        return "PJ001";
    }

    // Fungsi simpan untuk Admin (menerima array data_detail)
    public function simpan($data_pjm, $data_detail) {
        $this->db->trans_start();
        $this->db->insert('peminjaman', $data_pjm);
        $this->db->insert('detail_peminjaman', $data_detail);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Fungsi simpan untuk Anggota (proses pinjam mandiri)
    public function simpan_peminjaman($data_pinjam, $id_koleksi) {
        $this->db->trans_start();
        $this->db->insert('peminjaman', $data_pinjam);
        $this->db->insert('detail_peminjaman', [
            'id_peminjaman' => $data_pinjam['id_peminjaman'],
            'id_koleksi' => $id_koleksi
        ]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_riwayat_by_anggota($id_anggota) {
        $this->db->select('p.*, k.judul, k.penulis');
        $this->db->from('peminjaman p');
        $this->db->join('detail_peminjaman d', 'p.id_peminjaman = d.id_peminjaman');
        $this->db->join('koleksi k', 'd.id_koleksi = k.id_koleksi');
        $this->db->where('p.id_anggota', $id_anggota);
        $this->db->order_by('p.tgl_pinjam', 'DESC');
        return $this->db->get()->result_array();
    }

    public function cek_status_buku($id_koleksi) {
        $this->db->from('peminjaman p');
        $this->db->join('detail_peminjaman d', 'p.id_peminjaman = d.id_peminjaman');
        $this->db->where('d.id_koleksi', $id_koleksi);
        $this->db->where('p.status', 'Dipinjam');
        return $this->db->get();
    }

    public function generate_id_pinjam() {
        return $this->generate_id(); // Disatukan fungsinya agar konsisten
    }
}