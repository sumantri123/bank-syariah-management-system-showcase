<div class="sidebar-header">
    <div>
        <img src="{{ URL::asset(session("logoSidebar")) }}" style="width:30px;" alt="logo icon">   
    </div>
    <div>
        <h5 class="logo-text" style="font-size:18px">SYARIAH KLIK</h5>
    </div>
    <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
    </div>
</div>
<!--navigation-->
<ul class="metismenu" id="menu">
@if(Session::get('login_as')=="IT")
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-cloud-drizzle'></i></div>
            <div class="menu-title">Admin IT</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/lembaga"><i class="bx bx-right-arrow-alt"></i>Lembaga</a></li>
			<li> <a class="action" data-href="/resetPassword"><i class="bx bx-right-arrow-alt"></i>Ubah Password</a></li>
        </ul>
    </li>
@endif
	
@if(Session::get('login_as')=="EDP")
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-cloud-drizzle'></i>
            </div>
            <div class="menu-title">EDP</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/dataAwal"><i class="bx bx-right-arrow-alt"></i>Bentuk Data Awal</a></li>
            <li> <a class="action" data-href="/sa"><i class="bx bx-arrow-from-left"></i>Proses Saldo Awal</a></li>
            <li> <a class="action" data-href="/editPerkiraan"><i class="bx bx-arrow-from-left"></i>Edit Perkiraan</a></li>
            <li> <a class="action" data-href="/blackListGiro"><i class="bx bx-arrow-from-left"></i>Input Black List BI</a></li>
            <li> <a class="action" data-href="/daftarKliring"><i class="bx bx-arrow-from-left"></i>Peserta Kliring</a></li>
            <li> <a class="action" data-href="/nilaiTukar"><i class="bx bx-arrow-from-left"></i>Input Nilai Tukar</a></li>
            <li> <a class="action" data-href="/role"><i class="bx bx-arrow-from-left"></i>Security Level</a></li>
            <!-- <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Batas Wewenang</a></li> -->
            <li> <a class="action" data-href="/bukaTransaksi"><i class="bx bx-arrow-from-left"></i>Tutup Harian</a></li>
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Import") )

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-devices'></i></div>
            <div class="menu-title">Import</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('JM')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Import</a></li>
            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Telex Import</a></li> -->
            <li> <a class="action" data-href="/pembebananImport"><i class="bx bx-arrow-from-left"></i>Pembebanan Import</a></li>
            <li> <a class="action" data-href="/posAdmin/{{base64_encode('JM')}}"><i class="bx bx-arrow-from-left"></i>Pos Administratif</a></li>            
            <li> <a class="has-arrow" data-href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('JM')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                </ul>
            </li>
        
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Export") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-cube-alt'></i></div>
            <div class="menu-title">Export</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('JX')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Export</a></li>
            <li> <a class="action" data-href="/perhitunganWesel"><i class="bx bx-arrow-from-left"></i>Perhitungan Wesel Export</a></li>            
            <li> <a class="action" data-href="/posAdmin/{{base64_encode('JX')}}"><i class="bx bx-arrow-from-left"></i>Pos Administratif</a></li>            
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('JX')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                </ul>
            </li>
        
        </ul>
    </li>
	@endif
@if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Account Officer") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-clipboard'></i></div>
            <div class="menu-title">Account Officer</div>
        </a>
        <ul>
            <!-- <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Kebutuhan Modal Kerja</a>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Input Neraca & Laba Rugi</a></li>
                </ul>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Output</a>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Neraca Debitur</a></li>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Laba Rugi & Neraca Rekonsiliasi</a></li>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Perhitungan Kebutuhan Modal Kerja</a></li>
                        </ul>
                    </li>
                </ul>
            </li> -->
            <li> <a class="action" data-href="/lapKartuPinjaman"><i class="bx bx-arrow-from-left"></i>Tabel Angsuran Nasabah</a></li>
            <li> <a class="action" data-href="/infoAngsuran"><i class="bx bx-arrow-from-left"></i>Informasi Skedul Angsuran</a></li>            
            <li> <a class="action" data-href="/lapNasPi"><i class="bx bx-arrow-from-left"></i>Laporan Saldo Pembiayaan</a></li>                                
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Akuntansi") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-data'></i></div>
            <div class="menu-title">Akuntansi</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('AK')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Akuntansi</a></li>
            <li> <a class="action" data-href="/monRekPer"><i class="bx bx-arrow-from-left"></i>Monitoring rek. Perantara</a></li>                        
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lapDafPer"><i class="bx bx-arrow-from-left"></i>Daftar Perkiraan</a></li>
                    <li> <a class="action" data-href="/lapMonRekPer"><i class="bx bx-arrow-from-left"></i>Monitoring Rek Perantara</a></li>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('AK')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                    <li> <a class="action" data-href="/lapJH/{{base64_encode('AK')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Harian</a></li>
                    <!--<li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Neraca Saldo</a></li>-->
                    <li> <a class="action" data-href="/lapLabaRugi"><i class="bx bx-arrow-from-left"></i>Laba Rugi</a></li>
                    <!--<li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Komitmen Kontijensi</a></li>-->
                    <li> <a class="action" data-href="/lapNA"><i class="bx bx-arrow-from-left"></i>Posisi Keuangan</a></li>                                        
                </ul>
            </li>        
        </ul>
    </li>    
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Admin Kredit") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-desktop'></i></div>
            <div class="menu-title">Admin Pembiayaan</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/admKredit/{{base64_encode('LA')}}"><i class="bx bx-arrow-from-left"></i>Realisasi Pembiayaan</a></li>
            <li> <a class="action" data-href="/angsuran/{{base64_encode('LA')}}"><i class="bx bx-arrow-from-left"></i>Angsuran Pembiayaan</a></li>            
            <li> <a class="action" data-href="/posAdmin/{{base64_encode('LA')}}"><i class="bx bx-arrow-from-left"></i>Pos Administratif</a></li>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('LA')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Admin</a></li>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lapKartuPinjaman"><i class="bx bx-arrow-from-left"></i>Kartu Pembiayaan</a></li>
                    <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Tagihan Kartu Kredit</a></li> -->
                    <li> <a class="action" data-href="/posSaldoPin"><i class="bx bx-arrow-from-left"></i>Posisi Saldo Pembiayaan</a></li>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('LA')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>                    
                </ul>
            </li>        
        </ul>
    </li>    
    @endif
@if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Kliring") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-layer'></i></div>
            <div class="menu-title">Kliring</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/kliring/{{base64_encode('1')}}"><i class="bx bx-arrow-from-left"></i>Kliring Debet</a></li>
            <li> <a class="action" data-href="/kliring/{{base64_encode('2')}}"><i class="bx bx-arrow-from-left"></i>Kliring Kredit</a></li>
            <li> <a class="action" data-href="/kliring/{{base64_encode('3')}}"><i class="bx bx-arrow-from-left"></i>Kliring Pengembalian</a></li>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('PB')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Kliring</a></li>
            <li> <a class="has-arrow" data-href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lap_kliring_serah"><i class="bx bx-arrow-from-left"></i>Hasil Kliring Penyerahan</a></li>
                    <li> <a class="action" data-href="/lap_kliring_terima"><i class="bx bx-arrow-from-left"></i>Hasil Kliring Penerimaan</a></li>
                    <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Neraca Kliring</a></li>
                    <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Monitoring GWM</a></li>                     -->
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('PB')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                    <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Cetak Deal Slip</a></li> -->
                </ul>
            </li>        
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Deposito") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-shield-quarter'></i></div>
            <div class="menu-title">Deposito</div>
        </a>
        <ul>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Buka/Tutup Rek. Deposito</a>
                <ul>
                    <li> <a class="action" data-href="/tranDep/{{base64_encode('JD')}}"><i class="bx bx-arrow-from-left"></i>Deposito Berjangka</a></li>
                    <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Sertifikat Deposito</a></li>                     -->
                </ul>
            </li>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('JD')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Deposito</a></li>            
            <li> <a class="has-arrow" data-href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="has-arrow" data-href="javascript:;"><i class="bx bx-arrow-from-left"></i>Posisi Saldo Nasabah</a>
                        <ul>
                            <li> <a class="action" data-href="/posSaldoDepR"><i class="bx bx-arrow-from-left"></i>Deposito Mudharabah</a></li>
                            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Deposito Valas</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Sertifikat Deposito</a></li> -->
                        </ul>
                    </li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Saldo Mutasi Nasabah</a>
                        <ul>
                            <li> <a class="action" data-href="/samutNasDepR"><i class="bx bx-arrow-from-left"></i>Deposito Mudharabah</a></li>
                            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Deposito Valas</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Sertifikat Deposito</a></li> -->
                        </ul>
                    </li> 
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('JD')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>                    
                </ul>
            </li>        
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Transfer") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-repost'></i></div>
            <div class="menu-title">Transfer</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/testKey"><i class="bx bx-arrow-from-left"></i>Validasi Test Key</a></li>
            <li> <a class="action" data-href="/prefund"><i class="bx bx-arrow-from-left"></i>Input Prefund/RTGS</a></li>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('TF')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Transfer</a></li>
            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Monitoring Rekening Giro BI</a>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Posisi Giro BI, DPK & LDR</a></li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Cetak Deal Slip</a></li>                    
                </ul>
            </li> -->
            <li> <a class="action" data-href="/posAdmin/{{base64_encode('TF')}}"><i class="bx bx-arrow-from-left"></i>Pos Administratif</a></li>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('TF')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                    <li> <a class="action" data-href="/lapTestKey"><i class="bx bx-arrow-from-left"></i>Laporan Test Key</a></li>
                    <!-- <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Saldo Harian RAK</a>
                        <ul>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Cabang Malang</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Cabang Semarang</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Cabang Jakarta</a></li>
                        </ul>
                    </li>                     -->
                    <li> <a class="action" data-href="/lapRaapknDebet"><i class="bx bx-arrow-from-left"></i>Mutasi RAAPKN Debet</a></li>
                    <li> <a class="action" data-href="/lapRaapknKredit"><i class="bx bx-arrow-from-left"></i>Mutasi RAAPKN Kredit</a></li>
                </ul>
            </li>        
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Giro") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-buildings'></i></div>
            <div class="menu-title">Giro</div>
        </a>
        <ul>
            <li> <a class="action" data-href="/prk"><i class="bx bx-arrow-from-left"></i>Pencatatan Fasilitas PRK</a></li>
            <li> <a class="action" data-href="/tranGiro/{{base64_encode('JG')}}"><i class="bx bx-arrow-from-left"></i>Transaksi Rekening Giro</a></li>
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('JG')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian Giro</a></li>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Laporan</a>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Posisi Saldo Nasabah</a>
                        <ul>
                            <li> <a class="action" data-href="/posSaldoGiro"><i class="bx bx-arrow-from-left"></i>Giro Wadiah</a></li>
                            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Giro Valas</a></li> -->
                        </ul>
                    </li>                    
                    <li> <a class="action" data-href="/lapSaldoHagir"><i class="bx bx-arrow-from-left"></i>Saldo Harian</a></li>
                    <!-- <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Saldo Mutasi Nasabah</a>
                        <ul>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Rekening Giro</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Pinjaman Rekening Koran</a></li>                    
                        </ul>
                    </li>         -->
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('JG')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian</a></li>
                </ul>                
            </li>            
        </ul>
    </li>
    @endif
    @if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Teller") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-book-alt'></i></div>
            <div class="menu-title">Teller/Tabungan</div>
        </a>
        <ul>
            <li> <a href="javascript:;" class="has-arrow"><i class="bx bx-arrow-from-left"></i>Transaksi Tunai</a>
                <ul>
                    <li> <a class="action" data-href="/tranPemTun/{{base64_encode('AT')}}"><i class="bx bx-arrow-from-left"></i>Transaksi Pembayaran Tunai</a></li>
                    <li> <a class="action" data-href="/tranPenTun/{{base64_encode('AT')}}"><i class="bx bx-arrow-from-left"></i>Transaksi Penerimaan Tunai</a></li>
                </ul>
            </li>
            <li> <a class="action" data-href="/tranNonTu/{{base64_encode('AT')}}"><i class="bx bx-arrow-from-left"></i>Transaksi Non-Tunai</a></li>            
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Jual Beli Valas</a>
                <ul>
                    <li> <a class="action" data-href="/JBValasTunai"><i class="bx bx-arrow-from-left"></i>Tunai</a></li>
                    <li> <a class="action" data-href="/JBValasNonTunai"><i class="bx bx-arrow-from-left"></i>Non Tunai</a></li>
                </ul>
            </li>
            <li> <a class="action" data-href="/posAdmin/{{base64_encode('AT')}}"><i class="bx bx-arrow-from-left"></i>Pos Administratif</a></li>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan Kas Teller</a>
                        <ul>
                            <li> <a class="action" data-href="/lapMutasiHarian"><i class="bx bx-arrow-from-left"></i>Mutasi Harian</a></li>
                            <li> <a class="action" data-href="/lapPembayaran"><i class="bx bx-arrow-from-left"></i>Daftar Pembayaran</a></li>
                            <li> <a class="action" data-href="/lapPenerimaan"><i class="bx bx-arrow-from-left"></i>Daftar Penerimaan</a></li>
                        </ul>
                    </li>
					<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Posisi Saldo Tabungan</a>
                        <ul>
                            <li> <a class="action" data-href="/lapNasta"><i class="bx bx-arrow-from-left"></i>Wadiah</a></li>
                            <li> <a class="action" data-href="/lapNastaMud"><i class="bx bx-arrow-from-left"></i>Mudharabah</a></li>
                        </ul>
                    </li>
                    <!--<li> <a class="action" data-href="/posSaldoTab"><i class="bx bx-arrow-from-left"></i>Posisi Saldo Tabungan </a></li>-->
                    <li> <a class="action" data-href="/lapSaldoHatab"><i class="bx bx-arrow-from-left"></i>Saldo Harian Tabungan </a></li>
                    <li> <a class="action" data-href="/lapMutnas"><i class="bx bx-arrow-from-left"></i>Saldo Mutasi Nasabah </a></li>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('AT')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian </a></li>
                </ul>
            </li>                    
        </ul>
    </li>
    @endif
<?php// echo Session::get('login_as');?>
@if((Session::get('login_as')=="EDP") || (Session::get('login_as')=="Customer Service") )
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-user-circle'></i></div>
			<div class="menu-title">Customer Service</div>
        </a>
        <ul>
            <li> 
				<a class="has-arrow" href="javascript:void(0)"><i class="bx bx-arrow-from-left"></i>Entry CIF/Customer Number</a>
                <ul>
                    <li> <a class="action" data-href="/nasabahIndividu"><i class="bx bx-arrow-from-left"></i>Nasabah Individu</a></li>
                    <li> <a class="action" data-href="/nasabahBadanHukum"><i class="bx bx-arrow-from-left"></i>Nasabah Badan Hukum</a></li>
                    <!--<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Profil Risiko Nasabah</a>
                        <ul>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Nasabah Individu</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Nasabah Badan Hukum</a></li>
                        </ul>        
                    </li>-->
                </ul>
            </li>
            <li> 
				<a class="has-arrow" href="javascript:void(0)"><i class="bx bx-arrow-from-left"></i>Entry Rekening Nasabah</a>
                <ul>
                    <li> <a class="action" data-href="/nasabahGiro"><i class="bx bx-arrow-from-left"></i>Nasabah Giro</a></li>
					<li> <a class="action" data-href="/nasabahTabungan"><i class="bx bx-arrow-from-left"></i>Nasabah Tabungan</a></li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Nasabah Deposito</a>
                        <ul>
                            <li> <a class="action" data-href="/nasabahDeposito"><i class="bx bx-arrow-from-left"></i>Deposito</a></li>                            
                            <!-- <li> <a href="/sertifikatDeposito"><i class="bx bx-arrow-from-left"></i>Sertifikat Deposito</a></li> -->
                        </ul>
                    </li>
                    <li> <a class="action" data-href="/nasabahPinjaman"><i class="bx bx-arrow-from-left"></i>Nasabah Pembiayaan</a></li>
                </ul>        
            </li>            
            <li> <a class="action" data-href="/jurnalBagian/{{base64_encode('CS')}}";><i class="bx bx-arrow-from-left"></i>Jurnal Customer Service</a></li>            
            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Perhitungan Serdep</a></li>                         -->
            <li> 
				<a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Laporan</a>
                <ul>
					<li> <a class="action" data-href="/lapBlackListGiro"><i class="bx bx-arrow-from-left"></i>Black List BI </a></li>
                    <li> <a class="action" data-href="/lapCif"><i class="bx bx-arrow-from-left"></i>Customer Number </a></li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Nasabah Giro</a>
                        <ul>
                            <li> <a class="action" data-href="/lapNasGiRu"><i class="bx bx-arrow-from-left"></i>Giro Wadiah</a></li>
                            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Giro Valas</a></li>                             -->
                        </ul>
                    </li>
					<!-- <li> <a class="action" data-href="/lapNasta"><i class="bx bx-arrow-from-left"></i>Nasabah Tabungan </a></li>-->
					<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Nasabah Tabungan</a>
                        <ul>
                            <li> <a class="action" data-href="/lapNasta"><i class="bx bx-arrow-from-left"></i>Wadiah</a></li>
                            <li> <a class="action" data-href="/lapNastaMud"><i class="bx bx-arrow-from-left"></i>Mudharabah</a></li>
                        </ul>
                    </li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Nasabah Deposito </a>
                        <ul>
                            <li> <a class="action" data-href="/lapNasDep"><i class="bx bx-arrow-from-left"></i>Deposito Mudharabah</a></li>
                            <!-- <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Deposito Valas</a></li>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Sertifikat Deposito</a></li> -->
                        </ul>
                    </li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-arrow-from-left"></i>Nasabah Pembiayaan </a>
						<ul>
                            <li> <a class="action" data-href="/lapNasPi"><i class="bx bx-arrow-from-left"></i>Murabahah</a></li>
                            <li> <a class="action" data-href="/lapNasPiMd"><i class="bx bx-arrow-from-left"></i>Mudharabah</a></li>
                        </ul>
					</li>
                    <li> <a class="action" data-href="/lapJB/{{base64_encode('CS')}}"><i class="bx bx-arrow-from-left"></i>Jurnal Bagian </a></li>
                </ul>
            </li>                    
        </ul>
    </li>    
    @endif

</ul>
<!--end navigation-->
