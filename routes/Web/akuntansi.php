<?php
	
	//-- jurnal Bagian
	//Route::get('/jurnalBagian/QUs=','JurnalBagianController@index');
	
	// Monitoring Rekening Perantara
    Route::get('/monRekPer','MonRekPerantaraController@index');        
    Route::post('/getDataMon/lapMonRek','MonRekPerantaraController@getData');
	
	// Lap Daftar Perkiraan    
    Route::get('/lapDafPer','LapDafPerkiraanController@index');    
    Route::post('/getDataJson/lapDafPer','LapDafPerkiraanController@getData');
	
	// Lap Neraca Akhir
    Route::get('/lapNA','LapNeracaAkhirController@index');    
    Route::get('/getDatalapNA/lapNA','LapNeracaAkhirController@getData'); 
	
	// Lap Laba Rugi
    Route::get('/lapLabaRugi','LapLabaRugiController@index');    
    Route::post('/getDataLapLR/lapLR','LapLabaRugiController@getData');    

	// Lap Monitoring Rekening Perantara
    Route::get('/lapMonRekPer','LapMonRekPerantaraController@index');        
    Route::post('/getDataMon/lapMonRek','LapMonRekPerantaraController@getData');        
  