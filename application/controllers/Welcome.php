<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties] // <--- Tambahkan baris ini
class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}
}