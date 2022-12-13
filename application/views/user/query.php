<table id="example1" class="table table-bordered table-striped table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pinjam</th>
                                <th>ID Anggota</th>
                                <th>Nama</th>
                                <th>Pinjam</th>
                                <th>Balik</th>
                                <th style="width:10%">Status</th>
                                <th>Kembali</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $no=1;
                            foreach($pinjam->result_array() as $isi){
                                $anggota_id = $isi['no_reff'];
                                $ang = $this->db->query("SELECT * FROM tagihan WHERE no_reff = '$anggota_id'")->row();

                                // $pinjam_id = $isi['pinjam_id'];
                                // $denda = $this->db->query("SELECT * FROM tbl_denda WHERE pinjam_id = '$pinjam_id'");
                                // $total_denda = $denda->row();
                        ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $isi['stok_sebelumnya'];?></td>
                                <td><?= $isi['no_reff'];?></td>
                                <td><?= $ang->nama;?></td>
                                <td><?= $isi['tgl_pinjam'];?></td>
                                <td><?= $isi['tgl_balik'];?></td>
                                <td><?= $isi['status'];?></td>
                                <td>
                                    <?php 
                                        if($isi['tgl_kembali'] == '0')
                                        {
                                            echo '<p style="color:red;">belum dikembalikan</p>';
                                        }else{
                                            echo $isi['tgl_kembali'];
                                        }
                                    
                                    ?>
                                </td>
                                <td>
                                   
                                </td>
                                <td>
                                   <!--  -->
                                </td>
                            </tr>
                        <?php $no++;}?>
                        </tbody>
                    </table>