<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Meta;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $request->data = json_decode($request->data);
        $metas = (array) $request->data->meta;

        if( $request->method == "PUT" ) :
            $user = User::findOrFail((int) $request->user);
            $user->email = $request->data->email;
            if( ! empty($request->data->password) ) :
                $user->password = Hash::make($request->data->password);
            endif;

            if( 0 < count($user->metas) ) :
                foreach( $user->metas as $meta ) :
                    $meta->val = is_object($metas[$meta->key]) ? serialize((array) $metas[$meta->key]) : $metas[$meta->key];
                    $meta->save();
                endforeach;
            else :
                foreach( $metas as $key => $value ) :
                    $user->metas()->create([
                        'key'   => $key,
                        'val'   => is_object($value) ? serialize((array) $value) : $value,
                    ]);
                endforeach;
            endif;

            if( $request->file('avatar') ) :
                $request->file('avatar')->storeAs('public/avatars', 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension());

                $user->metas()->create([
                    'key'   => 'avatar',
                    'val'   => 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension()
                ]);
            endif;

            if( ! empty($request->data->password) ) :
                $emailName = $meta->fname;
                $emailTo = $request->data->email;
                $emailData = [
                    'name'      => $meta->fname .' '. $meta->lname,
                    'password'  => $request->data->password,
                    'loginURL'  => env('APP_URL')
                ];
                Mail::send('emails.password-changed', $emailData, function($message) use ($emailName, $emailTo) {
                    $message->to($emailTo, $emailName)
                    ->subject('CEFI Medical & Dentail Management Account');

                    $message->from(env('MAIL_USERNAME'), 'CEFI');
                });
            endif;

            $user->save();

            return response()->json($user);
        else :
            $user = User::create([
                'email'     => $request->data->email,
                'password'  => Hash::make($request->data->password),
                'role'      => $request->data->type,
            ]);
        
            foreach( $meta as $key => $value ) :
                $user->metas()->create([
                    'key'   => $key,
                    'val'   => is_object($value) ? serialize((array) $value) : $value,
                ]);
            endforeach;

            if( $request->file('avatar') ) :
                $request->file('avatar')->storeAs('public/avatars', 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension());

                $user->metas()->create([
                    'key'   => 'avatar',
                    'val'   => 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension()
                ]);
            endif;

            $emailName = $meta->fname;
            $emailTo = $request->data->email;
            $emailData = [
                'name'      => $meta->fname .' '. $meta->lname,
                'email'     => $request->data->email,
                'password'  => $request->data->password,
                'loginURL'  => env('APP_URL')
            ];
            Mail::send('emails.welcome', $emailData, function($message) use ($emailName, $emailTo) {
                $message->to($emailTo, $emailName)
                ->subject('CEFI Medical & Dentail Management Account');

                $message->from(env('MAIL_USERNAME'), 'CEFI');
            });
            
            return response()->json($user);
        endif;
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
            'number'    => NULL,
            'fname'     => NULL,
            'mname'     => NULL,
            'lname'     => NULL,
            'address'   => NULL,
            'birthday'  => NULL,
            'position'  => NULL,
            'weight'    => NULL,
            'height'    => NULL,
            'bp'        => NULL,
            'level'     => [
                'selected'    => $user->role == 'employee' ? 'employee' : 'elementary',
                'options'     => [
                    ['key'  => 'elementary', 'label' => 'Elementary'],
                    ['key'  => 'high-school', 'label' => 'High School'],
                    ['key'  => 'senior-high', 'label' => 'Senior High'],
                    ['key'  => 'college', 'label' => 'College'],
                ]
            ],
            'stage'     => $user->role == 'employee' ? NULL : 1,
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
                if( ! is_null($meta->val) ) :
                    $data['meta'][$meta->key] = is_serialized($meta->val) ? unserialize($meta->val) : $meta->val;
                else :
                    $data['meta'][$meta->key] = NULL;
                endif;
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
        if( $user = User::onlyTrashed()->whereId($id)->first() ) :
            $user->forceDelete();
        else :
            $user = User::findOrFail($id);
            $user->delete();
        endif;

        return response()->json($user);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if( ! $request->id ) return;
        
        User::withTrashed()->whereId($request->id)->restore();
        $user = User::findOrFail($request->id);

        return response()->json($user);
    }

    /**
     * Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $request->data = json_decode($request->data);
        $meta = $request->data->meta;

        $user = User::create([
            'email'     => $request->data->email,
            'password'  => Hash::make($request->data->password),
            'role'      => $request->data->type,
        ]);
       
        foreach( $meta as $key => $value ) :
            $user->metas()->create([
                'key'   => $key,
                'val'   => is_object($value) ? serialize((array) $value) : $value,
            ]);
        endforeach;

        if( $request->file('avatar') ) :
            $request->file('avatar')->storeAs('public/avatars', 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension());

            $user->metas()->create([
                'key'   => 'avatar',
                'val'   => 'user-avatar-'. $user->id .'.'. $request->file('avatar')->getClientOriginalExtension()
            ]);
        endif;

        $emailName = $meta->fname;
        $emailTo = $request->data->email;
        $emailData = [
            'name'      => $meta->fname .' '. $meta->lname,
            'email'     => $request->data->email,
            'password'  => $request->data->password,
            'loginURL'  => env('APP_URL')
        ];
        Mail::send('emails.welcome', $emailData, function($message) use ($emailName, $emailTo) {
            $message->to($emailTo, $emailName)
            ->subject('CEFI Medical & Dentail Management Account');

            $message->from(env('MAIL_USERNAME'), 'CEFI');
        });
        
        return response()->json($user);
    }
}
