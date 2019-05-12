<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Meta;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $users = User::all();
        if( $users ) :
            foreach( $users as $key => $val ) :
                $data[$key] = $val;
                $data[$key]['metas'] = $val->metas;
                $data[$key]['records'] = $val->records;
            endforeach;
        endif;

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'type',]);
        $data['password'] = Hash::make($data['password']);
        $meta = json_decode($request->meta, TRUE);

        $user = User::create($data);
       
        foreach( $meta as $key => $value ) :
            $user->metas()->create([
                'key'   => $key,
                'val'   => is_array($value) ? serialize($value) : $value,
            ]);
        endforeach;

        $user->records()->create([
            'type'  => 'info',
            'date'  => date('Y-m-d', strtotime('now')),
            'data'  => $request->records
        ]);
        
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $data = $user->toArray();
        $data['meta'] = [
            'address'   => NULL,
            'birthday'  => NULL,
            'position'  => NULL,
            'weight'    => NULL,
            'height'    => NULL,
            'bp'        => NULL,
            'gender'    => [
                'selected'  => 'm',
                'options'   => [
                    ['key' => 'm', 'label' => 'Male'],
                    ['key' => 'f', 'label' => 'Female'],
                ],
            ],
            'guardian'  => [
                'name'      => NULL,
                'phone'     => NULL,
            ],
        ];

        if( $user->metas ) :
            foreach( $user->metas as $meta ) :
                $data['meta'][$meta->key] = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;
            endforeach;
        endif;

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        
        $metas = json_decode($request->meta, TRUE);

        if( 0 < count($user->metas) ) :
            foreach( $user->metas as $meta ) :
                $meta->val = is_array($metas[$meta->key]) ? serialize($metas[$meta->key]) : $metas[$meta->key];
                $meta->save();
            endforeach;
        else :
            foreach( $metas as $key => $value ) :
                $user->metas()->create([
                    'key'   => $key,
                    'val'   => is_array($value) ? serialize($value) : $value,
                ]);
            endforeach;
        endif;

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if( $user->metas ) :
            foreach( $user->metas as $meta ) :
                $meta->delete();
            endforeach;
        endif;
        $user->delete();

        return response()->json($user);
    }
}
