<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    public function index()
    {
        $response = Data::get();

        return response()->json([
            'status' => 'success',
            'data'   => $response
        ]);
    }

    public function edit(Request $request)
    {
        $response = Data::find($request->id);
        // $response = Data::where('id', '=', $request->id)->first();

        return response()->json([
            'status' => 'success',
            'data'   => $response
        ]);
    }

    public function insert(Request $request)
    {
        
        // dd($request->file);
        $data = new Data;

        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->agama = $request->agama;
        $data->status = $request->status;

        if ($request->hasFile('file')) {

            //get filename with extension
            $filenamewithextension = $request->file('file')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('file')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . uniqid() . '.' . $extension;

            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore, fopen($request->file('file'), 'r+'));

            //Store $filenametostore in the database

            $data->file = $filenametostore;
        }

        // $response = Data::create([
        //     'nik' => $request->nik
        // ]);

        if($data->save()){

            $response = $data;

            return response()->json([
                'status' => 'success',
                'data'   => $response
            ]);

        }else{
            return response()->json([
                'status' => 'error',
                'data'   => []
            ]);
        }
    }

    public function update(Request $request)
    {
        $data = Data::find($request->id);

        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->agama = $request->agama;
        $data->status = $request->status;
        if($data->file){
         $data->file = $request->file;
        }

        // $response = Data::where('id', '=', $request->id)->update([
        //     'nik' => $request->nik
        // ]);

        if($data->save()){

            $response = $data;

            return response()->json([
                'status' => 'success',
                'data'   => $response
            ]);

        }else{
            return response()->json([
                'status' => 'error',
                'data'   => []
            ]);
        }
    }

    public function delete(Request $request)
    {
        // $response = Data::destroy($request->id);

        $response = Data::find($request->id);
        $response->delete();

        return response()->json([
            'status' => 'success',
            'data'   => $response
        ]);
    }
}
