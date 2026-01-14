<?php
class M_Admin extends CI_Model {

    public function get_stats() {
        return [
            'total_buku'    => $this->db->count_all('koleksi'),
            'total_anggota' => $this->db->count_all('anggota'),
            'total_pinjam'  => $this->db->where('status', 'Dipinjam')->from('peminjaman')->count_all_results()
        ];
    }

    public function get_recent_activities() {
        return $this->db->select('p.id_peminjaman, a.nama, k.judul, p.status')
            ->from('peminjaman p')
            ->join('anggota a', 'p.id_anggota = a.id_anggota')
            ->join('detail_peminjaman dp', 'p.id_peminjaman = dp.id_peminjaman')
            ->join('koleksi k', 'dp.id_koleksi = k.id_koleksi')
            ->order_by('p.id_peminjaman', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();
    }
}