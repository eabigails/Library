<?php
class M_Wishlist extends CI_Model {

    public function get_wishlist_by_anggota($id_anggota) {
    $this->db->select('wishlist.*, koleksi.judul, koleksi.penulis, kategori.nama_kategori');
    $this->db->from('wishlist');
    $this->db->join('koleksi', 'wishlist.id_koleksi = koleksi.id_koleksi');
    $this->db->join('kategori', 'koleksi.id_kategori = kategori.id_kategori', 'left');
    $this->db->where('wishlist.id_anggota', $id_anggota);
    
    $result = $this->db->get()->result_array();
    return $result ? $result : array(); // Kembalikan array kosong jika tidak ada data
}

    public function hapus_wishlist($id_wishlist, $id_anggota) {
        $this->db->where('id_wishlist', $id_wishlist);
        $this->db->where('id_anggota', $id_anggota);
        return $this->db->delete('wishlist');
    }
}