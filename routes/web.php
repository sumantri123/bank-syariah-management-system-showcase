<?php

use App\Http\Middleware\CSP1;
use App\Http\Middleware\CSP2;


Route::middleware([CSP1::class,CSP2::class,'throttle:30,1','middleware' => 'auth'])->group(function () {

    /* Route::get('/backend/', function () {
        return view('dashboard');
    }); */
		
	Route::post('/login_as', 'LoginController@login_as');
	
	include __DIR__.'/Web/edp.php';
	include __DIR__.'/Web/cs.php';
	include __DIR__.'/Web/giro.php';
	include __DIR__.'/Web/transfer.php';
	include __DIR__.'/Web/akuntansi.php';
	include __DIR__.'/Web/adminkredit.php';
	include __DIR__.'/Web/deposito.php';
	include __DIR__.'/Web/teller.php';
	include __DIR__.'/Web/import.php';
	include __DIR__.'/Web/eksport.php';
	include __DIR__.'/Web/kliring.php';

    
    // Admin IT
    Route::get('/lembaga','AdminITController@index');    
    Route::get('/getDataJson/data_lembaga', 'AdminITController@getDataLembaga');  
    Route::post('/lembaga','AdminITController@store');   
    Route::put('/lembaga/{id}','AdminITController@update');
	Route::get('/resetPassword','AdminITController@reset_view');    	
	Route::put('/resetPassword/{id}','AdminITController@updatePassAdmin');  
	
    Route::get('/viewKelas/{id}','AdminITController@view_kelas');
    Route::get('/getDataJson/data_kelas/{id}', 'AdminITController@getDataKelas');  
    Route::get('/createGenNilaiTukar/{id}','AdminITController@CreateGenNilaiTukar');       
    Route::get('/createGenUser/{id}','AdminITController@CreateGenUser'); 
	Route::get('/createGenMasterKliring/{id}','AdminITController@CreateGenKliring');       
	Route::get('/createGenMasterPerkiraan/{id}','AdminITController@CreateGenPerkiraan');   	
	Route::get('/createGenHakAkses/{id}','AdminITController@CreateGenHakAkses');       
    Route::post('/kelas','AdminITController@storeKelas');   
    Route::put('/kelas/{id}','AdminITController@updateKelas');
    Route::get('/viewUser/{id}','AdminITController@view_user');
	Route::get('/viewHakAkses/{id}','AdminITController@view_hak_akses');
    Route::get('/getDataJson/data_user/{id}', 'AdminITController@getDataUser');
	Route::get('/getDataJson/data_hak_akses/{id}', 'AdminITController@getDataHakAkses');
    Route::put('/reset_password/{id}','AdminITController@updatePassword');
    Route::post('/update/NonAktifKelas', 'AdminITController@NonAktifKelas');
    Route::post('/update/AktifKelas', 'AdminITController@AktifKelas');
	Route::get('/delete/kelasMaster/{id}','AdminITController@destroy');
	Route::get('/delete/lembaga/{id}','AdminITController@destroyLembaga');	
	Route::get('/deleteMenuUser/{id}','AdminITController@deleteMenu');  
    Route::get('/addMenuUser/{id}/{id2}','AdminITController@saveMenu');  

    // Jurnal Bagian
    $tes="JM-JX-AK-CS-PB-JD-TF-JG-LA-AT";    
    Route::post('/getIdPerJurnalBagian','JurnalBagianController@getIdPerkiraan');
    //Route::get('/jurnalBagian/{kode}','JurnalBagianController@index')->middleware('open:'.$tes.'');      
    Route::get('/jurnalBagian/{kode}','JurnalBagianController@index');      
    Route::post('/jurnalBagian','JurnalBagianController@store')->middleware('open');
    Route::get('/delete/jurnalBagian/{id}','JurnalBagianController@destroy');
    Route::put('/jurnalBagian/{id}','JurnalBagianController@update');    

    // Jurnal Bagian Detail
    Route::post('/jurnalBagianDet','JurnalBagianController@storeDet');
    Route::get('/delete/jurnalBagianDet/{id}/{id2}','JurnalBagianController@destroyDet');
    Route::get('/total/jurnalBagianDet/{id}','JurnalBagianController@totalDet');
    Route::post('/search/jurnalBagianDet','JurnalBagianController@search');

    // Pos Admin
    Route::post('/getIdPerposAdmin','PosAdminController@getIdPerkiraan');
    Route::get('/posAdmin/{kode}','PosAdminController@index');        
    Route::post('/posAdmin','PosAdminController@store')->middleware('open');
    Route::get('/delete/posAdmin/{id}','PosAdminController@destroy');
    Route::put('/posAdmin/{id}','PosAdminController@update');

    // Pos Admin Detail
    Route::post('/posAdminDet','PosAdminController@storeDet');
    Route::get('/delete/posAdminDet/{id}/{id2}','PosAdminController@destroyDet');
    Route::get('/total/posAdminDet/{id}','PosAdminController@totalDet');
    Route::post('/search/posAdminDet','PosAdminController@search');

    // Kurs Footer
    Route::get('/kursFooter','DashboardController@kurs_footer');      
    Route::get('/', 'DashboardController@index');

    // Data Awal
    Route::get('/dataAwal','DataAwalController@index');       
    Route::get('/createRekTab/{id}','DataAwalController@CreateRekTab');       
    Route::get('/createRekTabMud/{id}','DataAwalController@CreateRekTabMud');
    Route::get('/createRekGiro/{id}','DataAwalController@CreateRekGiro');       
    Route::get('/createRekDep/{id}','DataAwalController@CreateRekDep');       
    Route::get('/createRekPin/{id}','DataAwalController@CreateRekPin');       
	Route::get('/createRekPinMh/{id}','DataAwalController@createRekPinMh');       
	Route::get('/createRekPinMd/{id}','DataAwalController@createRekPinMd');       
    Route::post('/uploadData', 'DataAwalController@importExcel');   
    Route::get('/getDataJson/data_sa_file', 'DataAwalController@getData');

    // Monitoring Rekening Perantara
    Route::get('/monRekPer','MonRekPerantaraController@index');        
    Route::post('/getDataMon/lapMonRek','MonRekPerantaraController@getData');        

    // Laporan Jurnal Bagian    
    Route::get('/lapJB/{kode}','LapJurnalBagianController@index');        
    Route::post('/getDataLapJB/lapJB','LapJurnalBagianController@getData'); 
    
    // Laporan Jurnal Harian    
    Route::get('/lapJH/{kode}','LapJurnalHarianController@index');        
    Route::post('/getDataLapJH/lapJH','LapJurnalHarianController@getData');    	

    // Informasi Schedule Angsuran
    Route::get('/infoAngsuran','InfoAngsuranController@index');        
    Route::post('/getDataInfoAngsur/infoAngsuran','InfoAngsuranController@getData');    	
});

Route::middleware([CSP1::class,CSP2::class,'throttle:30,1'])->group(function () {	
    // Login
	Route::get('/logout', 'LoginController@logout')->name('login');
	Route::get('/login', 'LoginController@showLoginForm')->name('login');
	Route::get('/admin', 'LoginController@adminLoginForm')->name('admin');
	Route::post('/getDataLogin', 'LoginController@login');
	Route::post('/getDataLoginAdmin', 'LoginController@admin_login');
	Route::post('/login','LoginController@login')->name('karyawan.login');
	Route::post('/getDataJson/UserKelas', 'LoginController@getDataUserKelas');
	
    // Kelas    
	Route::get('/getDataJson/MasterKelas', 'LoginController@getDataKelas');
	    
});