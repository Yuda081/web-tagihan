<?php

?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">    
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
               
        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <a class="btn btn-info btn-sm" href="<?= base_url('admin/sales')?>">Kembali</a>
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Tagihan</h3>
                </div>
              </div>
            </div>
            <div class="nav-wrapper mx-3">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <?php 
                      $i = 0;
                      foreach($dataAkun as $row):
                      $i++;
                    ?>
                    <li class="nav-item mb-4">
                        <a class="nav-link mb-sm-3 mb-md-0 tab-nav" id="tabs-icons-text-<?=$i?>-tab" data-toggle="tab" href="#tabs-icons-text-<?=$i?>" role="tab" aria-controls="tabs-icons-text-<?=$i?>" aria-selected="true"><?= $row->nama ?></a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="card" style="border-top: 2px solid white">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <?php 
                          $a=0;
                          $debit = 0;
                          $kredit = 0;
                          
                          for($i=0;$i<$jumlah;$i++) :                          
                          $a++;
                          $s=0;
                          $deb = $saldo[$i];
                        ?>
                        <div class="tab-pane fade" id="tabs-icons-text-<?= $a ?>" role="tabpanel" aria-labelledby="tabs-icons-text-<?= $a ?>-tab">
                            <div class="row">
                              <div class="col">
                                <b><?= $data[$i][$s]->nama ?></b>
                              </div>
                              <div class="col text-right">
                                <b><?= $data[$i][$s]->no_reff ?></b>
                              </div>
                            </div>
                            <p class="description">
                              <div class="table-responsive p-0">
                              <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                  <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Nama</th>
                                    <th rowspan="2">Kode Flash</th>
                                    <th rowspan="2">Jenis Voucher</th>
                                    <th rowspan="2">Stok Sebelumnya</th>
                                    <th rowspan="2">Stok Tambahan</th>
                                    <th rowspan="2">Stok Awal</th>
                                    <th rowspan="2">Stok Akhir</th>
                                    <th rowspan="2">Stok Terjual</th>
                                    <th colspan="2" class="text-center">Total Tagihan</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    for($j=0;$j<count($data[$i]);$j++):
                                      $timeStampt = strtotime($data[$i][$j]->tgl_transaksi);
                                      $bulan = date('m',$timeStampt);

                                      $tahun = date('Y',$timeStampt);
                                      $tgl = date('d',$timeStampt);
                                      $bulan = medium_bulan($bulan);
                                  ?>
                                  <tr>
                                      <td><?= $tgl.' '.$bulan.' '.$tahun ?></td>
                                      <td><?= $data[$i][$j]->nama ?></td>
                                      <td><?= $data[$i][$j]->kode_flash ?></td>
                                      <td><?= $data[$i][$j]->jenis_voucher ?></td>
                                      <td><?= $data[$i][$j]->stok_sebelumnya ?></td>
                                      <td><?= $data[$i][$j]->stok_tambahan ?></td>
                                      <td><?= $data[$i][$j]->stok_awal ?></td>
                                      <td><?= $data[$i][$j]->stok_akhir ?></td>
                                      <td><?= $data[$i][$j]->stok_terjual ?></td>
                                       <?php 
                                        if($data[$i][$j]->jenis_saldo=='debit'){
                                      ?>
                                        <td>
                                          <?= 'Rp. '.number_format($data[$i][$j]->total_bayar,0,',','.') ?>
                                        </td>
                                      <?php 
                                        }else{
                                      ?>
                                      <?php } ?>
                                      <?php
                                        if($deb[$j] =="debit"){
                                          $debit = $debit + $deb[$j]->total_bayar;
                                        }else{
                                          $kredit = $kredit + $deb[$j]->total_bayar;
                                        }
  
                                        $hasil = $debit-$kredit;
                                      ?>
                                  </tr>
                                  <?php endfor ?>
                                  <?php
                                    $debit = 0;
                                    $kredit = 0;
                                  ?>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td class="text-center" colspan="4"><b>Total</b></td>
                                  <?php if($hasil>=0){ ?>
                                    <td class="text-success"><?= 'Rp. '.number_format($hasil,0,',','.') ?></td>
                                  <?php }else{ ?>
                                    <td class="text-danger"><?= 'Rp. '.number_format(abs($hasil),0,',','.') ?></td>
                                  <?php } ?>

                                </tbody>
                              </table>
                        </div>
                        <?php endfor ?>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
