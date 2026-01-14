<?php
class M_Anggota extends CI_Model {

    public function get_all_anggota() {
        return $this->db->order_by('tgl_bergabung', 'DESC')->get('anggota')->result_array();
    }

    public function hapus_anggota($id) {
        // 1. Hapus di user_login (berdasarkan ref_id)
        $this->db->delete('user_login', ['ref_id' => $id]);
        
        // 2. Hapus di tabel anggota
        return $this->db->delete('anggota', ['id_anggota' => $id]);
    }
}