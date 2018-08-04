<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\LayananResource;
use App\Layanan;
use Excel;
use DB;
use Alert;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layanan = Layanan::paginate('15');
        return LayananResource::collection($layanan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'aktivitas' => 'required',
            'tahun' => 'required',

        ]);

        $layanan = new Layanan;
        $layanan->aktivitas = $request->aktivitas;
        $layanan->tahun = $request->tahun;
        $layanan->save();

        return response()->json(['layanan' => Layanan::find($layanan->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $request->validate([
            'aktivitas' => 'required',
            'tahun' => 'required'
        ]);

        $layanan = Layanan::find($id);
        $layanan->aktivitas = $request->aktivitas;
        $layanan->tahun = $request->tahun;
        $layanan->save();

        return response()->json(['message' => 'Berhasil diUpdate']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $layanan = Layanan::find($id);
        $layanan->delete();
        return response()->json(['message' => 'Berhasil dihapus']);
    }

    public function layananExport()
    {
        $layanan = Layanan::select('aktivitas', 'tahun')->get();
        return Excel::create('data_layanan', function($excel) use ($layanan) {
            $excel->sheet('mysheet', function($sheet) use ($layanan) {
                $sheet->fromArray($layanan);
            });
        })->download('xlsx');
    }

    public function layananImport(Request $request)
    {
        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $layanan = new Layanan();
                    $layanan->aktivitas = $value->aktivitas;
                    $layanan->tahun = $value->tahun;
                    $layanan->save();
                }
            }
        }
        Alert::success('Upload file berhasil', 'Success')->autoclose(3000);
        return redirect()->back();
    }
}
