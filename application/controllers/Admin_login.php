<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{	
		$this->form_validation->set_rules('email_admin', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password_admin', 'Password', 'trim|required');
		if($this->form_validation->run () == false){

			$data['title'] = 'Login Administrator';
			$this->load->view('admin/admin_login', $data);
		}else{
			//validasi suksess
			$this->_loginadmin();
		}
	}

	private function _loginadmin()
	{
		$email = $this->input->post('email_admin');
		$password = $this->input->post('password_admin');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		// jika usernya ada
		if($user){
			//jika usernya aktif
			if($user['is_active'] == 1){
				//cek password
				if(password_verify($password, $user['password'])) {
					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);
					if($user['role_id'] == 1) {
						redirect ('admin');
					}else{
						$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					session tidak ditemukan</div>');
					redirect('admin_login');
					}
				}else{
					$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					wrong password!</div>');
					redirect('admin_login');
				}
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				This email has been activated!</div>');
				redirect('admin_login');
			}
		}else{
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
			Email is not registered!</div>');
			redirect('admin_login');
		}
	}
}