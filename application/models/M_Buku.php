<?php
class M_Buku extends CI_Model {

    public function get_all_buku() {
        $this->db->select('k.*, kt.nama_kategori');
        $this->db->from('koleksi k');
        $this->db->join('kategori kt', 'k.id_kategori = kt.id_kategori', 'left');
        $this->db->order_by('k.id_koleksi', 'DESC');
        return $this->db->get()->result_array();
    }

    public function generate_id() {
        $last = $this->db->select('id_koleksi')->order_by('id_koleksi', 'DESC')->limit(1)->get('koleksi')->row_array();
        if ($last) {
            $lastId = (int) substr($last['id_koleksi'], 2);
            return "BK" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
        }
        return "BK001";
    }

    public function hapus_buku($id) {
        // Ambil nama file gambar sebelum hapus data
        $buku = $this->db->get_where('koleksi', ['id_koleksi' => $id])->row_array();
        if ($buku) {
            if ($buku['gambar'] != "default.jpg") {
                $path = FCPATH . 'uploads/koleksi/' . $buku['gambar'];
                if (file_exists($path)) { unlink($path); }
            }
            return $this->db->delete('koleksi', ['id_koleksi' => $id]);
        }
        return false;
    }
}