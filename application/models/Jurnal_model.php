<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model{
    private $table = 'tagihan';

    public function getJurnal(){
        return $this->db->get($this->table)->result();
    }

    public function getJurnalById($id){
        return $this->db->where('id',$id)->get($this->table)->row();
    }

    public function countJurnalNoReff($noReff){
        return $this->db->where('no_reff',$noReff)->get($this->table)->num_rows();
    }

    public function getJurnalByYear(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getJurnalByYearAndMonth(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('month(tgl_transaksi)')
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getAkunInJurnal(){
        return $this->db->select('tagihan.no_reff,reseller.no_reff,reseller.nama')
                    ->from($this->table)            
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('reseller.no_reff','ASC')
                    ->group_by('reseller.nama')
                    ->get()
                    ->result();
    }

    public function countAkunInJurnal(){
        return $this->db->select('tagihan.no_reff,reseller.no_reff,reseller.nama')
                    ->from($this->table)            
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('reseller.no_reff','ASC')
                    ->group_by('reseller.nama')
                    ->get()
                    ->num_rows();
    }

    public function getJurnalByNoReff($noReff){
        return $this->db->select('tagihan.id,tagihan.tgl_transaksi,reseller.nama,tagihan.no_reff,tagihan.jenis_saldo,tagihan.kode_flash,tagihan.jenis_voucher,tagihan.stok_sebelumnya,tagihan.stok_tambahan,tagihan.stok_awal,tagihan.stok_akhir,tagihan.stok_terjual,tagihan.total_bayar,tagihan.tgl_input')
                    ->from($this->table)            
                    ->where('tagihan.no_reff',$noReff)
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('tagihan.id,tagihan.tgl_transaksi,reseller.nama,tagihan.no_reff,tagihan.jenis_saldo,tagihan.kode_flash,tagihan.jenis_voucher,tagihan.stok_sebelumnya,tagihan.stok_tambahan,tagihan.stok_awal,tagihan.stok_akhir,tagihan.stok_terjual,tagihan.total_bayar,tagihan.tgl_input')
                    ->from($this->table)            
                    ->where('tagihan.no_reff',$noReff)
                    ->where('month(tagihan.tgl_transaksi)',$bulan)
                    ->where('year(tagihan.tgl_transaksi)',$tahun)
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffSaldo($noReff){
        return $this->db->select('tagihan.jenis_saldo,tagihan.total_bayar')
                    ->from($this->table)            
                    ->where('tagihan.no_reff',$noReff)
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffSaldoMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('tagihan.kode_flash,tagihan.total_bayar')
                    ->from($this->table)            
                    ->where('tagihan.no_reff',$noReff)
                    ->where('month(tagihan.tgl_transaksi)',$bulan)
                    ->where('year(tagihan.tgl_transaksi)',$tahun)
                    ->join('reseller','tagihan.no_reff = reseller.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalJoinAkun(){
        return $this->db->select('tagihan.id,tagihan.tgl_transaksi,reseller.nama,tagihan.no_reff,tagihan.jenis_saldo,tagihan.kode_flash,tagihan.jenis_voucher,tagihan.stok_sebelumnya,tagihan.stok_tambahan,tagihan.stok_awal,tagihan.stok_akhir,tagihan.stok_terjual,tagihan.total_bayar,tagihan.tgl_input')
                        ->from($this->table)
                        ->join('reseller','tagihan.no_reff = reseller.no_reff')
                        ->order_by('tgl_transaksi','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetail($bulan,$tahun){
        return $this->db->select('tagihan.id,tagihan.tgl_transaksi,reseller.nama,tagihan.no_reff,tagihan.jenis_saldo,tagihan.kode_flash,tagihan.jenis_voucher,tagihan.stok_sebelumnya,tagihan.stok_tambahan,tagihan.stok_awal,tagihan.stok_akhir,tagihan.stok_terjual,tagihan.total_bayar,tagihan.tgl_input')
                        ->from($this->table)
                        ->where('month(tagihan.tgl_transaksi)',$bulan)
                        ->where('year(tagihan.tgl_transaksi)',$tahun)
                        ->join('reseller','tagihan.no_reff = reseller.no_reff')
                        ->order_by('tgl_transaksi','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getTotalSaldoDetail($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('total_bayar')
                        ->from($this->table)
                        ->where('month(tagihan.tgl_transaksi)',$bulan)
                        ->where('year(tagihan.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function getTotalSaldo($jenis_saldo){
        return $this->db->select_sum('total_bayar')
                        ->from($this->table)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function insertJurnal($data){
        return $this->db->insert($this->table,$data);
    }

    public function updateJurnal($id,$data){
        return $this->db->where('id',$id)->update($this->table,$data);
    }

    public function deleteJurnal($id){
        return $this->db->where('id',$id)->delete($this->table);
    }

    public function getDefaultValues(){
        return [
            'tgl_transaksi'=>date('Y-m-d'),
            'no_reff'=>'',
            'jenis_saldo'=>'',
            'saldo'=>'',
        ];
    }

    public function getValidationRules(){
        return [
            [
                'field'=>'tgl_transaksi',
                'label'=>'Tanggal Transaksi',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'no_reff',
                'label'=>'Nama Akun',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'jenis_saldo',
                'label'=>'Jenis Saldo',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'saldo',
                'label'=>'Saldo',
                'rules'=>'trim|required|numeric'
            ],
        ];
    }

    public function validate(){
        $rules = $this->getValidationRules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">','</span>');
        return $this->form_validation->run();
    }

    // public function index_kp($index_data=NULL)
    // {
    //     $this->db->select('user.*, tagihan.nama AS email, tagihan.id,tagihan.tgl_transaksi,tagihan.no_reff,tagihan.jenis_saldo,tagihan.kode_flash,tagihan.jenis_voucher,tagihan.stok_sebelumnya,tagihan.stok_tambahan,tagihan.stok_awal,tagihan.stok_akhir,tagihan.stok_terjual,tagihan.total_bayar,tagihan.tgl_input');
    //     $this->db->join('tagihan','user.email = tagihan.email');
    //     $this->db->from()
    // }
}