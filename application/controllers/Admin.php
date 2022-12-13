<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
	public function __construct(){
        parent::__construct();
        $this->load->helper(['url','form','sia','tgl_indo']);
        $this->load->library(['session','form_validation']);
        $this->load->model('Akun_model','akun',true);
        $this->load->model('Jurnal_model','jurnal',true);
    }

	public function index()
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = 'Dashboard';
		// $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer_admin');
	}

	public function sales()
	{
		$data['title'] = 'Sales';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['listTagihan'] = $this->jurnal->getJurnalByYearAndMonth();
		$data['tahun'] = $this->jurnal->getJurnalByYear();
		$this->load->model('Form_model', 'form');
		$data['voucher'] = $this->form->voucher()->result_array();

		$data['daftar'] = $this->form->data()->result_array();
		$data['cek'] = $this->form->pengguna()->result_array();

		$this->form_validation->set_rules('jenis', 'Jenis', 'required');
		$this->form_validation->set_rules('harga', 'Harga', 'required');

		if($this->form_validation->run() == false) {

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/sales', $data);
		$this->load->view('templates/footer_admin');
		}else{
			$data = [
				'voucher' => $this->input->post('jenis'),
				'harga_satuan' => $this->input->post('harga'),
			];

			$this->db->insert('jenis_voucher', $data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					Jenis Voucher di tambahkan</div>');
					redirect('admin/sales');
		}
	}

	public function form_tagihan()
	{
		$data['title'] = 'Tambah Tagihan';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['tgl_input'] = date('Y-m-d H:i:s'); 
		$this->load->model('Form_model', 'form');

		$data['daftar'] = $this->form->data()->result_array();
		$data['voucher'] = $this->form->voucher()->result_array();
		$data['reff'] = (object) $this->jurnal->getDefaultValues();

		$this->form_validation->set_rules('dtp', 'Tanggal', 'required');
		$this->form_validation->set_rules('reseller', 'Reseller', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('flash', 'Flash', 'required');
		$this->form_validation->set_rules('voucher', 'Voucher', 'required');
		$this->form_validation->set_rules('sebelum', 'Sebelum', 'required');
		$this->form_validation->set_rules('tambahan', 'Tambahan', 'required');
		$this->form_validation->set_rules('awal', 'Awal', 'required');
		$this->form_validation->set_rules('akhir', 'Akhir', 'required');
		$this->form_validation->set_rules('terjual', 'Terjual', 'required');
		$this->form_validation->set_rules('bayar', 'Bayar', 'required');

		if($this->form_validation->run() == false){	

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/form_tagihan', $data);
			$this->load->view('templates/footer_admin');
		}else{
			$email = $this->input->post('email');
			$tgl_input = date('Y-m-d H:i:s'); 
			$dtp = $this->input->post('dtp');
			$reseller = $this->input->post('reseller');
			$flash = $this->input->post('flash');
			$voucher = $this->input->post('voucher');
			$sebelum = $this->input->post('sebelum');
			$tambahan = $this->input->post('tambahan');
			$awal = $this->input->post('awal');
			$akhir = $this->input->post('akhir');
			$terjual = $this->input->post('terjual');
			$bayar = $this->input->post('bayar');

			$data = array(
				'email' => $email,
				'tgl_input' => $tgl_input,
				'tgl_transaksi' => $dtp,
				'nama' => $reseller,
				'no_reff' => $flash,
				'kode_flash' => $flash,
				'jenis_voucher' => $voucher,
				'stok_sebelumnya' => $sebelum,
				'stok_tambahan' => $tambahan,
				'stok_awal' => $awal,
				'stok_akhir' => $akhir,
				'stok_terjual' => $terjual,
				'jenis_saldo' => 'debit',
				'total_bayar' => $bayar
			);

			$this->db->insert('tagihan', $data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					Tagihan berhasil ditambahkan</div>');
					redirect('admin/sales');
		}

	}

	public function detail()
	{
		$content = 'admin/sales_detail';
        $titleTag = 'sales';
        
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) ||empty($tahun)){
            redirect('admin/sales');
        }
        
        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        $data = null;
        $saldo = null;

        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Data Buku Besar Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('admin/sales');
        }

        $jumlah = count($data);

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo'));
    }

    public function reseller()
    {
    	$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    	$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('kode', 'Kode', 'required');

		if($this->form_validation->run() == false) {
		}else{
			$data = [
				'nama' => $this->input->post('nama'),
				'flashnet' => $this->input->post('kode'),
				'no_reff' => $this->input->post('kode'),
			];

			$this->db->insert('reseller', $data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					Reseller Baru telah di tambahkan</div>');
					redirect('admin/sales');
		}
    }

    public function pengguna()
    {
    	$data['title'] = 'Pengguna';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();	

		$this->load->model('Form_model', 'form');

		$data['pengguna'] = $this->form->pengguna()->result_array();

		$this->form_validation->set_rules('status', 'Status', 'required');

		if($this->form_validation->run() == false){

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/pengguna', $data);
			$this->load->view('templates/footer_admin');
		}else{

			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$role = $this->input->post('role');
			$status = $this->input->post('status');
			$email = $this->input->post('email');

			$this->db->set('nama', $nama);
			$this->db->set('alamat', $alamat);
			$this->db->set('role_id', $role);
			$this->db->set('is_active', $status);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->db->set('nama', $nama);
			$this->db->set('alamat', $alamat);
			$this->db->set('role_id', $role);
			$this->db->set('is_active', $status);
			$this->db->where('email', $email);
			$this->db->where('email', $email);
			$this->db->update('user_reseller');

			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					Berhasil di perbaharui</div>');
					redirect('admin/pengguna');
		}

    }

    public function logout() 
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
		You have been logged out!</div>');
		redirect('auth');
	}
}