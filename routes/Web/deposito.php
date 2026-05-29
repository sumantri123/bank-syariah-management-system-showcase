<?php
	
	//-- jurnal Bagian
	//Route::get('/jurnalBagian/SkQ=','JurnalBagianController@index');
	
	// Saldo Mutasi Nasabah
	Route::get('/samutNasDepR','LapMutasiNasabahDepController@index'); 
	Route::post('/getData/lapMutdep','LapMutasiNasabahDepController@getData'); 
	
	// Posisi Saldo Nasabah Deposito
    Route::get('/posSaldoDepR','LapNasabahDepositoController@index');  

	// Transaksi Deposito
    Route::get('/tranDep/{kode}','TransaksiDepositoController@index'); 
    Route::post('/tranDep','TransaksiDepositoController@store')->middleware('open');
    Route::put('/tranDep/{id}','TransaksiDepositoController@update');
    Route::get('/delete/tranDep/{id}','TransaksiDepositoController@destroy');
    Route::post('/search/tranDep','TransaksiDepositoController@search');
    Route::get('/GetPerkiraanDep1/{id}','TransaksiDepositoController@getIdPerkiraan1');
    Route::get('/GetPerkiraanDep2/{id}','TransaksiDepositoController@getIdPerkiraan2');
    Route::post('/GetDataDep','TransaksiDepositoController@getDataDep');