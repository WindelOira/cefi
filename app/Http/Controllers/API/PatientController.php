<?php

namespace App\Http\Controllers\API;

use App\Patient;
use App\Meta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $patients = Patient::all();
        if( $patients ) :
            foreach( $patients as $key => $val ) :
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
        $data = $request->only(['name', 'type',]);
        $meta = json_decode($request->meta, TRUE);

        $patient = Patient::create($data);
       
        foreach( $meta as $key => $value ) :
            $patient->metas()->create([
                'key'   => $key,
                'val'   => is_array($value) ? serialize($value) : $value,
            ]);
        endforeach;

        $patient->records()->create([
            'type'  => 'info',
            'date'  => date('Y-m-d', strtotime('now')),
            'data'  => $request->records
        ]);
        
        return response()->json($patient);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        $data = $patient->toArray();
        $data['meta'] = [
            'address'   => NULL,
            'birthday'  => NULL,
            'position'  => NULL,
            'weight'    => NULL,
            'height'    => NULL,
            'bp'        => NULL,
            'guardian'  => [
                'name'      => NULL,
                'phone'     => NULL,
            ],
        ];

        if( $patient->metas ) :
            foreach( $patient->metas as $meta ) :
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
        $patient = Patient::findOrFail($id);
        $patient->name = $request->name;
        
        $metas = json_decode($request->meta, TRUE);

        if( 0 < count($patient->metas) ) :
            foreach( $patient->metas as $meta ) :
                $meta->val = is_array($metas[$meta->key]) ? serialize($metas[$meta->key]) : $metas[$meta->key];
                $meta->save();
            endforeach;
        else :
            foreach( $metas as $key => $value ) :
                $patient->metas()->create([
                    'key'   => $key,
                    'val'   => is_array($value) ? serialize($value) : $value,
                ]);
            endforeach;
        endif;

        $patient->save();

        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        if( $patient->metas ) :
            foreach( $patient->metas as $meta ) :
                $meta->delete();
            endforeach;
        endif;
        $patient->delete();

        return response()->json($patient);
    }
}
