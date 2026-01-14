<?php
class M_Katalog extends CI_Model {

    public function get_kategori() {
        return $this->db->order_by('nama_kategori', 'ASC')->get('kategori')->result_array();
    }

    public function get_buku($q = null, $kategori = null) {
        $this->db->select('k.*, g.nama_kategori');
        $this->db->from('koleksi k');
        $this->db->join('kategori g', 'k.id_kategori = g.id_kategori', 'left');

        if ($q) {
            $this->db->group_start();
            $this->db->like('k.judul', $q);
            $this->db->or_like('k.penulis', $q);
            $this->db->group_end();
        }

        if ($kategori) {
            $this->db->where('k.id_kategori', $kategori);
        }

        $this->db->order_by('k.judul', 'ASC');
        return $this->db->get()->result_array();
    }

    public function cek_tersedia($id_koleksi) {
    $this->db->select('*');
    $this->db->from('peminjaman p');
    $this->db->join('detail_peminjaman d', 'p.id_peminjaman = d.id_peminjaman');
    $this->db->where('d.id_koleksi', $id_koleksi);
    $this->db->where('p.status', 'Dipinjam');
    return $this->db->get();
}
}