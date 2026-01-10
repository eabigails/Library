class Auth extends CI_Controller {

    public function login() {
        if ($_POST) {
            $user = $this->db->get_where('user_login', [
                'username' => $this->input->post('username')
            ])->row();

            if ($user && password_verify($this->input->post('password'), $user->password)) {

                $this->session->set_userdata([
                    'id_user' => $user->id_user,
                    'role' => $user->role,
                    'ref_id' => $user->ref_id
                ]);

                if ($user->role == 'Anggota') {
                    redirect('home'); // BALIK KE HOME
                } else {
                    redirect('dashboard');
                }
            }
        }

        $this->load->view('login_view');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('home');
    }
}
