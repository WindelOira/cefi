<?php

namespace App\Http\Controllers\API;

use App\Record;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['date'] = date('Y-m-d', strtotime(isset($data['date']) ? $data['date'] : 'now'));
        $data['data'] = is_array($data['data']) ? $data['data'] : json_decode($data['data'], TRUE);

        $record = Record::create($data);

        return response()->json($record);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = Record::findOrFail($id);

        return response()->json($record);
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
        $data = $request->all();

        $record = Record::findOrFail($id);
        $record->date = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : $record->date;
        $record->data = is_array($data['data']) ? $data['data'] : json_decode($data['data'], TRUE);
        $record->save();

        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return response()->json($record);
    }
}
