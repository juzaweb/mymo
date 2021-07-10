<?php
/**
 * @package    mymocms/mymocms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/mymocms/mymocms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/25/2021
 * Time: 9:02 PM
 */

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'Backend\UserController@index')->name('admin.users.index');

    Route::get('/get-data', 'Backend\UserController@getData')->name('admin.users.get-data');

    Route::get('/create', 'Backend\UserController@form')->name('admin.users.create');

    Route::get('/edit/{id}', 'Backend\UserController@form')->name('admin.users.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\UserController@save')->name('admin.users.save');

    Route::post('/bulk-actions', 'Backend\UserController@bulkActions')->name('admin.users.bulk-actions');
});
