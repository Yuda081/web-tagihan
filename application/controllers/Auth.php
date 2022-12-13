<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index ()
	{
		$this->form_validation->set_rules('email', 'Email' , 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if($this->form_validation->run() == false){

			$data ['title'] = 'Login Page';
			$this->load->view('auth/index', $data);

		}else {
			//validasi success
			$this->_login();
		}
	}

	private function _login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		// jika usernya ada
		if($user){
			//jika usernya aktif
			if($user['is_active'] == 'aktif'){
				//cek password
				if(password_verify($password, $user['password'])) {
					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);
					if($user['role_id'] == 'Admin') {
						redirect ('admin');
					}else{
						redirect ('user');
					}
				}else{

					$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					Kata sandi salah!</div>');
					redirect('auth');
				}

			}else{

				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				email ini belum di aktifkan!</div>');
				redirect('auth');
			}

		}else {
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
			Email yang dimasukkan belum terdaftar!</div>');
			redirect('auth');
		}
	} 


	public function registrasi()
	{
		$this->form_validation->set_rules('nama', 'Name', 'required|trim');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
		$this->form_validation->set_rules('nomor', 'Nomor', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'This email has already registered!'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches'=> 'password dont match!',
			'min_length' => 'password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if($this->form_validation->run() == false){

			$data ['title'] = 'Registration Page';
			// $this->load->view('templates/header', $data);
			// $this->load->view('auth/registrasi_mahasiswa');
			// $this->load->view('templates/footer');
			$this->load->view('auth/daftar', $data);
		} else {
			$email = $this->input->post('email', true);

			$data = [
				'nama' => htmlspecialchars($this->input->post('nama', true)),
				'alamat' => $this->input->post('alamat'),
				'nomor' => $this->input->post('nomor'),
				'email' => htmlspecialchars($email),
				// 'name_puskes' => htmlspecialchars($this->input->post('namapuskes', true)),
				// 'alamat_puskes' => htmlspecialchars($this->input->post('alamatpuskes', true)),
				// 'image' => 'default.png',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 'Reseller',
				'is_active' => 'tidak aktif',
				'date_created' => time() 
			];

			// token
			// $token = base64_encode(random_bytes(32));
			// $user_token = [
			// 	'email' => $email,
			// 	'token' => $token,
			// 	'date_created' => time()
			

			$this->db->insert('user', $data);
			$this->db->insert('user_reseller', $data);
			// $this->db->insert('user_token', $user_token);

			// $this->_sendEmailMahasiswa($token, 'verify');

			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
			Selamat akun anda berhasil di buat, silahkan hubungi Admin untuk proses Aktivasi</div>');
			redirect('auth');
		}
	}

	private function _sendEmailMahasiswa($token, $type)
	{
		$config = [
			'protocol' => 'smtp',
			'smtp_host' =>'ssl://smtp.googlemail.com',
			'smtp_user' => 'hmjakuntansi55@gmail.com',
			'smtp_pass' => 'Akuntansi123',
			'smtp_port' => 465,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		];
		$this->load->library('email', $config);
		$this->email->initialize($config);

		$this->email->from('hmjakuntansi55@gmail.com','Admin HMJ Akuntansi');
		$this->email->to($this->input->post('email'));

		if($type == 'verify') {

			$this->email->subject('Akun Verifikasi');
			$this->email->message('Silahkan klik link berikut untuk verifikasi akun Mahasiswa anda : <a href="'.base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Aktivasi</a>');
		}else if($type == 'forgot') {
			$this->email->subject('Reset Password');
			$this->email->message('Silahkan klik link berikut untuk reset password anda : <a href="'.base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
		}



		if ($this->email->send()) {
			return true;
		}else {
			echo $this->email->print_debugger();
			die;
		}
	}

	public function verify()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if($user){
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

			if($user_token) {
				if(time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');

					$this->db->delete('user_token', ['email' => $email]);
					$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					'. $email .' sudah diaktifkan! silahkan login</div>');
					redirect('auth/login');

				}else{
					$this->db->delete('user', ['email' => $email]);
					$this->db->delete('user_token', ['email' => $email]);


					$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					Aktivasi akun gagal! Token kadaluarsa</div>');
					redirect('auth/login');
				}

			}else {

				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				Aktivasi akun gagal! Token tidak valid</div>');
				redirect('auth/login');
			}

		}else {
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
			Aktivasi akun gagal! Email yang dimasukkan salah</div>');
			redirect('auth/login');
		}
	}
}