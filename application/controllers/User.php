<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller 
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
		$data['title'] = 'Dashboard | Reseller';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		

		$this->load->view('templates/header_user', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer_user');
	}

	public function histori()
	{
		$data['title'] = 'History | Reseller';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['tes'] = $this->db->get_where('tagihan', ['email'=> $this->session->userdata('email')])->result_array();
		

		$this->load->view('templates/header_user', $data);
		$this->load->view('user/history', $data);
		$this->load->view('templates/footer_user');
	}

	public function detail()
	{
		$session = $this->db->get_where('tagihan', ['email' => $this->session->userdata('email')])->row_array();
		$content = 'user/history_detail';
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

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo','session'));
    }

    public function data()
    {
    	
	    	$query = $this->db->get_where('tagihan',array('email'=> $this->session->userdata('email')));
			echo "<pre>";
			print_r($query->result());
			echo "</pre>";
		
			
    }
}