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
    Route::get('records/{type?}/{start?}/{end?}/{gender?}/{level?}', function($type = null, $start = null, $end = null, $gender = 'all', $level = 'all') {
        if( ! is_null($start) || ! is_null($end) ) :
            $records = App\Record::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
        else :
            $records = App\Record::whereType($type)->get();
        endif;

        if( 'all' != $gender && 'all' != $level ) :
            $rec = $records->reject(function($record) use ($gender, $level) {
                $genderStatus = TRUE;
                $levelStatus = TRUE;
                $metas = $record->user->metas;

                foreach($metas as $meta) :
                    if( $meta->key == 'gender' ) :
                        $g = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;

                        $levelStatus = $g['selected'] != $gender;
                    endif;

                    if( $meta->key == 'level' ) :
                        $l = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;

                        $genderStatus = $l['selected'] != $level;
                    endif;
                endforeach;

                return ! $genderStatus && ! $levelStatus ? FALSE : TRUE;
            });
            $records = [];

            foreach($rec as $r) :
                $records[] = $r;
            endforeach;
        else :
            if( 'all' != $gender ) :
                $rec = $records->reject(function($record) use ($gender) {
                    $metas = $record->user->metas;
    
                    foreach($metas as $meta) :
                        if( $meta->key == 'gender' ) :
                            $g = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;
    
                            return $g['selected'] != $gender;
                        endif;
                    endforeach;
                });
                $records = [];
    
                foreach($rec as $r) :
                    $records[] = $r;
                endforeach;  
            endif;
    
            if( 'all' != $level ) :
                $rec = $records->reject(function($record) use ($level) {
                    $metas = $record->user->metas;
    
                    foreach($metas as $meta) :
                        if( $meta->key == 'level' ) :
                            $l = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;
    
                            return $l['selected'] != $level;
                        endif;
                    endforeach;
                });
                $records = [];

                foreach($rec as $r) :
                    $records[] = $r;
                endforeach;  
            endif;
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
    Route::post('user/restore', 'API\UserController@restore');
    Route::post('user/register', 'API\UserController@register');
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
Route::get('users/{type?}/{trashed?}', function($type = null, $trashed = 0) {
    if( $trashed ) :
        $users = App\User::onlyTrashed()->whereRole($type)->get();
    else :
        $users = App\User::whereRole($type)->get();
    endif;
    
    foreach( $users as $user ) :
        if( $user->metas ) :
            foreach( $user->metas as $key => $meta ) :
                $user->metas[$meta->key] = $meta->val;
                unset($user->metas[$key]);
            endforeach;
        endif;
    endforeach;

    return response()->json($users);
})->where([ 
    'type' => '[a-z]+',
    'trashed' => '[0-1]+'
]);

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