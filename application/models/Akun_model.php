<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun_model extends CI_Model{
    private $table = 'reseller';

    public function getAkun(){
        return $this->db->get($this->table)->result();
    }

    public function getAkunByMonthYear($bulan,$tahun){
        return $this->db->select('reseller.no_reff,reseller.nama,reseller.flashnet')
                        ->from($this->table)
                        ->where('month(tagihan.tgl_transaksi)',$bulan)
                        ->where('year(tagihan.tgl_transaksi)',$tahun)
                        ->join('tagihan','tagihan.no_reff = reseller.no_reff')
                        ->group_by('reseller.nama')
                        ->order_by('tagihan.no_reff')
                        ->get()
                        ->result();
    }

    public function countAkunByNama($str){
        return $this->db->where('nama_reff',$str)->get($this->table)->num_rows();
    }

    public function countAkunByNoReff($str){
        return $this->db->where('no_reff',$str)->get($this->table)->num_rows();
    }

    public function getAkunByNo($noReff){
        return $this->db->where('no_reff',$noReff)->get($this->table)->row();
    }

    public function insertAkun($data){
        return $this->db->insert($this->table,$data);
    }

    public function updateAkun($noReff,$data){
        return $this->db->where('no_reff',$noReff)->update($this->table,$data);
    }

    public function deleteAkun($noReff){
        return $this->db->where('no_reff',$noReff)->delete($this->table);
    }

    public function getDefaultValues(){
        return [
            'no_reff'=>'',
            'nama_reff'=>'',
            'keterangan'=>''
        ];
    }

    public function getValidationRules(){
        return [
            [
                'field'=>'no_reff',
                'label'=>'No.Reff',
                'rules'=>'trim|required|numeric|callback_isNoAkunThere'
            ],
            [
                'field'=>'nama_reff',
                'label'=>'Nama Reff',
                'rules'=>'trim|required|callback_isNamaAkunThere'
            ],
            [
                'field'=>'keterangan',
                'label'=>'Keterangan',
                'rules'=>'trim|required'
            ],
        ];
    }

    public function validate(){
        $rules = $this->getValidationRules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">','</span>');
        return $this->form_validation->run();
    }
}