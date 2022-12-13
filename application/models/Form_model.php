<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model
{
	public function data()
	{
		return $this->db->get('reseller');
	}

	public function voucher()
	{
		return $this->db->get('jenis_voucher');
	}

	public function pengguna()
	{
		return $this->db->get('user');
	}
}
