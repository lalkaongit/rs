<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/404', function () {
    return view('errors/404');
})->name('404');

Auth::routes();


Route::get('/', function () {
    return view('welcome');
});


Route::get('/reg_institution', 'RegisterInstitutionController@index')->name('reg_inst');
Route::post('/reg_institution', 'RegisterInstitutionController@create');

Route::group(['middleware' => ['guest']], function () {

});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function() {

    //admin

    Route::get('/', 'Admin\AccountController@index')->name('admin');
    //specialties

    Route::get('/disciplines', 'Admin\DisciplineController@index')->name('disciplines');
    Route::get('/disciplines/add', 'Admin\DisciplineController@addDiscipline')->name('disciplines.add');
    Route::post('/disciplines/add', 'Admin\DisciplineController@addRequestDiscipline');
    Route::delete('/disciplines/delete', 'Admin\DisciplineController@deleteDiscipline')->name('disciplines.delete');
    Route::get('/disciplines/edit/{id}', 'Admin\DisciplineController@editDiscipline')
                ->where('id', '\d+')
                ->name('disciplines.edit');
    Route::post('/disciplines/edit/{id}', 'Admin\DisciplineController@editRequestDiscipline')
                ->where('id', '\d+');


    Route::get('/specialties', 'Admin\SpecialtiesController@index')->name('specialties');
    Route::get('/specialties/add', 'Admin\SpecialtiesController@addSpecialties')->name('specialties.add');
    Route::post('/specialties/add', 'Admin\SpecialtiesController@addRequestSpecialties');
    Route::delete('/specialties/delete', 'Admin\SpecialtiesController@deleteSpecialties')->name('specialties.delete');
    Route::get('/specialties/edit/{id}', 'Admin\SpecialtiesController@editSpecialty')
                ->where('id', '\d+')
                ->name('specialties.edit');
    Route::post('/specialties/edit/{id}', 'Admin\SpecialtiesController@editRequestSpecialty')
                ->where('id', '\d+');



    Route::get('/groups', 'Admin\GroupsController@index')->name('groups');
    Route::get('/groups/add', 'Admin\GroupsController@addGroup')->name('groups.add');
    Route::post('/groups/add', 'Admin\GroupsController@addRequestGroup');
    Route::delete('/groups/delete', 'Admin\GroupsController@deleteGroup')->name('groups.delete');
    Route::get('/groups/edit/{id}', 'Admin\GroupsController@editGroup')
                ->where('id', '\d+')
                ->name('groups.edit');
    Route::post('/groups/edit/{id}', 'Admin\GroupsController@editRequestGroup')
                ->where('id', '\d+');


    Route::get('/import', 'Admin\ImportController@getImport')->name('import');
    Route::post('/import_parse', 'Admin\ImportController@parseImport')->name('import_parse');
    Route::post('/import_process', 'Admin\ImportController@processImport')->name('import_process');

    Route::get('/importgroup', 'Admin\GroupImportController@getImport')->name('importgroup');
    Route::post('/import_parse_group', 'Admin\GroupImportController@parseImport')->name('import_parse_group');
    Route::post('/import_process_group', 'Admin\GroupImportController@processImport')->name('import_process_group');

    Route::get('/importspec', 'Admin\SpecialtiesImportController@getImport')->name('importspec');
    Route::post('/import_parse_spec', 'Admin\SpecialtiesImportController@parseImport')->name('import_parse_spec');
    Route::post('/import_process_spec', 'Admin\SpecialtiesImportController@processImport')->name('import_process_spec');

    Route::get('/importdiscp', 'Admin\DisciplineImportController@getImport')->name('importdiscp');
    Route::post('/import_parse_discp', 'Admin\DisciplineImportController@parseImport')->name('import_parse_discp');
    Route::post('/import_process_discp', 'Admin\DisciplineImportController@processImport')->name('import_process_discp');









});


Route::post('/update', ['as' => '/update', 'uses' => 'Teacher\RSController@updateLecturesRS'])->name('update');
Route::post('/updatepr', ['as' => '/updatepr', 'uses' => 'Teacher\RSController@updatePracticalsRS'])->name('updatepr');
Route::post('/updatet', ['as' => '/updatet', 'uses' => 'Teacher\RSController@updateTRS'])->name('updatet');
Route::post('/updatetest', ['as' => '/updatetest', 'uses' => 'Teacher\RSController@updateTestRS'])->name('updatetest');
Route::post('/updateti', ['as' => '/updateti', 'uses' => 'Teacher\RSController@updateTIRS'])->name('updateti');

Route::post('/updatetmaini', ['as' => '/updatetmaini', 'uses' => 'Teacher\RSController@updateTImainRS'])->name('updatetmaini');
Route::post('/updatemain', ['as' => '/updatemain', 'uses' => 'Teacher\RSController@updateMAINRS'])->name('updatemain');
Route::post('/update-date', ['as' => '/update-date', 'uses' => 'Teacher\RSController@updateDATE'])->name('update-date');
Route::post('/plus', ['as' => '/plus', 'uses' => 'Teacher\RSController@plus'])->name('plus');
Route::post('/minus', ['as' => '/minus', 'uses' => 'Teacher\RSController@minus'])->name('minus');
Route::post('/update-att', ['as' => '/update-att', 'uses' => 'Teacher\RSController@updateATT'])->name('update-att');
Route::post('/save-value', ['as' => '/save-value', 'uses' => 'Teacher\RSController@saveVAL'])->name('save-value');
Route::post('/save-theme', ['as' => '/save-theme', 'uses' => 'Teacher\RSController@saveTHM'])->name('save-theme');





Route::post('/getrand', ['as' => '/getrand', 'uses' => 'Teacher\RSController@getrandRS'])->name('getrand');



Route::get('/clear', function() {
    Artisan::call('cache:clear');
    return "Кэш очищен.";
});

Route::get('/rs/view/lectures/{id}', 'Teacher\RSController@viewLecturesRS')
            ->where('id', '\d+')
            ->name('rs.lectures');



Route::group(['middleware' => ['teacher'], 'prefix' => 'teacher'], function() {
    Route::get('/', 'Teacher\AccountController@index')->name('teacher');

    Route::delete('/delete', 'Teacher\RSController@deleteRS')->name('rss.delete');

    Route::get('/rs', 'Teacher\RSController@index')->name('rs');
    Route::get('/rs/add', 'Teacher\RSController@addRS')->name('rs.add');
    Route::post('/rs/add', 'Teacher\RSController@addRequestRS');
    Route::delete('/rs/delete', 'Teacher\RSController@deleteRS')->name('rs.delete');
    Route::get('/rs/edit/{id}', 'Teacher\RSController@editRS')
                ->where('id', '\d+')
                ->name('rs.edit');
    Route::post('/rs/edit/{id}', 'Teacher\RSController@editRequestRS')
                ->where('id', '\d+');
    Route::get('/rs/view/{id}', 'Teacher\RSController@viewRS')
                ->where('id', '\d+')
                ->name('rs.view');

    Route::get('/rs/view/lectures/{id}', 'Teacher\RSController@viewLecturesRS')
                ->where('id', '\d+')
                ->name('rs.lectures');
    Route::post('/rs/edit/lectures/{id}', 'Teacher\RSController@editRequestLecturesRS')
                ->where('id', '\d+');

    Route::get('/rs/view/practicals/{id}', 'Teacher\RSController@viewPracticalsRS')
                ->where('id', '\d+')
                ->name('rs.practicals');

    Route::get('/rs/view/labs/{id}', 'Teacher\RSController@viewLabsRS')
                ->where('id', '\d+')
                ->name('rs.labs');

    Route::get('/rs/view/bonuses/{id}', 'Teacher\RSController@viewBonusesRS');

    Route::get('/rs/view/att/{id}', 'Teacher\RSController@viewATTRS');

    Route::get('/rs/view/bf/{id}', 'Teacher\RSController@viewBF');



});
