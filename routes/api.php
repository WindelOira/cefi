<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function() {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('register', 'Auth\AuthController@register');
    Route::get('refresh', 'Auth\AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'Auth\AuthController@user');
        Route::post('logout', 'Auth\AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::resource('category', 'API\CategoryController')->except([
        'index', 'show',
    ]);

    Route::resource('record', 'API\RecordController');
    Route::get('records/{type?}', function($type = null) {
        return response()->json(App\Record::whereType($type)->get());
    })->where([
        'type'  => '[a-z]+',
    ]);
    Route::get('records/{type?}/{id?}', function($type = null, $id = null) {
        return response()->json(App\Record::whereType($type)->whereUserId($id)->get());
    })->where([
        'type'  => '[a-z]+', 
        'id'    => '[0-9]+',
    ]);
    Route::get('records/{type?}/{year?}/{month?}/{gender?}', function($type = null, $year = null, $month = null, $gender = null) {
        if( ! is_null($year) || ! is_null($month) ) :
            if( ! is_null($year) && is_null($month) ) :
                $records = App\Record::whereType($type)->whereYear('created_at', $year)->get();
            elseif( is_null($year) && ! is_null($month) ) :
                $records = App\Record::whereType($type)->whereMonth('created_at', ($month + 1))->get();
            else :
                $records = App\Record::whereType($type)->whereYear('created_at', $year)->whereMonth('created_at', ($month + 1))->get();
            endif;
        else :
            $records = App\Record::whereType($type)->get();
        endif;

        if( $gender != 'null' ) :
            $rec = $records->reject(function($record) use ($gender) {
                $metas = $record->user->metas;

                foreach($metas as $meta) :
                    if( $meta->key == 'gender' ) :
                        $g = unserialize($meta->val);

                        return $g['selected'] != $gender;
                    endif;
                endforeach;
            });
            $records = [];

            foreach($rec as $r) :
                $records[] = $r;
            endforeach;  
        endif;
        
        return response()->json($records);
    })->where([
        'type'      => '[a-z]+', 
        'year'      => '[0-9]+',
        'month'     => '[0-9]+',
        'gender'    => '[a-z]+',
    ]);

    Route::resource('user', 'API\UserController')->except([
        'index', 'store', 'show',
    ]);
    Route::resource('patient', 'API\PatientController')->except([
        'index', 'store', 'show',
    ]);
});

Route::middleware('auth:api')->get('/api/user', function (Request $request) {
    return $request->user();
});

Route::resource('user', 'API\UserController')->except([
    'update', 'destroy',
]);
Route::get('users/{type?}', function($type = null) {
    return response()->json(App\User::whereRole($type)->get());
})->where('type', '[a-z]+');

Route::resource('patient', 'API\PatientController')->except([
    'update', 'destroy',
]);
Route::get('patients/{type?}', function($type = null) {
    return response()->json(App\Patient::whereType($type)->get());
})->where('type', '[a-z]+');

Route::resource('category', 'API\CategoryController')->except([
    'store', 'update', 'destroy',
]);
Route::get('categories/{type?}', function($type = null) {
    return response()->json(App\Category::whereType($type)->get());
})->where('type', '[a-z](?:[a-z]|-(?!-))+');