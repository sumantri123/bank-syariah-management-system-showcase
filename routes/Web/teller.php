<?php
	
    // Transaksi Valas Tunai    
    Route::get('/JBValasTunai','JBValasTunaiController@index');      
    Route::post('/jurnalBagianDetValas','JBValasTunaiController@storeDet');
    Route::post('/jurnalValasTunai','JBValasTunaiController@store')->middleware('open');
    Route::post('/search/jurnalBagianDetValas','JBValasTunaiController@search');
    Route::get('/total/jurnalBagianDetValas/{id}','JBValasTunaiController@totalDet');
    Route::post('/getIdPerJBValas','JBValasTunaiController@getIdPerkiraan');            
    Route::get('/delete/jurnalBagianValas/{id}','JBValasTunaiController@destroy');
    Route::put('/jurnalValasTunai/{id}','JBValasTunaiController@update');    
    //Route::get('/delete/jurnalBagianDetValas/{id}/{id2}','JBValasTunaiController@destroyDet');

    // Transaksi Non Tunai Valas
    Route::get('/JBValasNonTunai','TransaksiNonTunaiValasController@index'); 
    Route::post('/tranNonTuVa','TransaksiNonTunaiValasController@store')->middleware('open');
    Route::put('/tranNonTuVa/{id}','TransaksiNonTunaiValasController@update');
    Route::get('/delete/tranNonTuVa/{id}','TransaksiNonTunaiValasController@destroy');
    Route::post('/search/tranNonTuVa','TransaksiNonTunaiValasController@search');
    // Route::post('/GetPerkiraanVa1','TransaksiNonTunaiValasController@getIdPerkiraan1');
    // Route::get('/GetPerkiraanVa2/{id}','TransaksiNonTunaiValasController@getIdPerkiraan2');

	// Transaksi Pembayaran Tunai
    Route::get('/tranPemTun/{kode}','TransaksiPembayaranTunaiController@index'); 
    Route::post('/tranPemTun','TransaksiPembayaranTunaiController@store')->middleware('open'); 
    Route::put('/tranPemTun/{id}','TransaksiPembayaranTunaiController@update');
    Route::get('/delete/tranPemTun/{id}','TransaksiPembayaranTunaiController@destroy');
    Route::post('/search/tranPemTun','TransaksiPembayaranTunaiController@search');  
    Route::get('/GetKodeTransPem/{id}','TransaksiPembayaranTunaiController@getKodeTransaksi');
	
	// Transaksi Penerimaan Tunai
    Route::get('/tranPenTun/{kode}','TransaksiPenerimaanTunaiController@index'); 
    Route::post('/tranPenTun','TransaksiPenerimaanTunaiController@store')->middleware('open');
    Route::put('/tranPenTun/{id}','TransaksiPenerimaanTunaiController@update');
    Route::get('/delete/tranPenTun/{id}','TransaksiPenerimaanTunaiController@destroy');
    Route::post('/search/tranPenTun','TransaksiPenerimaanTunaiController@search'); 
    Route::get('/GetKodeTransPen/{id}','TransaksiPenerimaanTunaiController@getKodeTransaksi'); 
	
	// Transaksi Non Tunai
    Route::get('/tranNonTu/{kode}','TransaksiNonTunaiController@index'); 
    Route::post('/tranNonTu','TransaksiNonTunaiController@store')->middleware('open');
    Route::put('/tranNonTu/{id}','TransaksiNonTunaiController@update');
    Route::get('/delete/tranNonTu/{id}','TransaksiNonTunaiController@destroy');
    Route::post('/search/tranNonTu','TransaksiNonTunaiController@search');
    Route::post('/GetPerkiraan1','TransaksiNonTunaiController@getIdPerkiraan1');
    Route::get('/GetPerkiraan2/{id}','TransaksiNonTunaiController@getIdPerkiraan2');
	
	// Posisi Saldo Nasabah Tabungan
    Route::get('/posSaldoTab','LapNasabahTabunganController@index');  

    // Laporan Mutasi Harian    
    Route::get('/lapMutasiHarian','LapMutasiHarianController@index');        
    Route::post('/getData/lapMutasiHarian','LapMutasiHarianController@getData'); 

    // Laporan Saldo Mutasi Nasabah
    Route::get('/lapMutnas','LapMutasiNasabahController@index');        
    Route::post('/getData/lapMutnas','LapMutasiNasabahController@getData'); 

    // Laporan Daftar Pembayaran
    Route::get('/lapPembayaran','LapPembayaranController@index');        
    Route::post('/getData/lapPembayaran','LapPembayaranController@getData'); 

    // Laporan Daftar Penerimaan
    Route::get('/lapPenerimaan','LapPenerimaanController@index');        
    Route::post('/getData/lapPenerimaan','LapPenerimaanController@getData'); 

    // Laporan Saldo Harian Tabungan
    Route::get('/lapSaldoHatab','LapSaldoHarianTabController@index');        
    Route::post('/getData/lapSaldoHatab','LapSaldoHarianTabController@getData');     