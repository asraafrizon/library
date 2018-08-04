<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Koleksi;
use App\Http\Resources\Koleksi as KoleksiResource;
use Excel;
use DB;
use Alert;

class KoleksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $koleksi = Koleksi::paginate('15');
        return KoleksiResource::collection($koleksi);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $request->validate([
            'jurnal' => 'required',
            'tahun' => 'required',
            'jumlah' => 'required'
        ]);

        $koleksi = new Koleksi;
        $koleksi->jurnal = $request->jurnal;
        $koleksi->tahun = $request->tahun;
        $koleksi->jumlah = $request->jumlah;
        $koleksi->save();

        return response()->json(['koleksi' => Koleksi::find($koleksi->id)]);

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
            'jurnal' => 'required',
            'tahun' => 'required',
            'jumlah' => 'required'
        ]);

        $koleksi = Koleksi::find($id);
        $koleksi->jurnal = $request->jurnal;
        $koleksi->tahun = $request->tahun;
        $koleksi->jumlah = $request->jumlah;
        $koleksi->save();

        return response()->json(['message' => 'Berhasil di update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $koleksi = Koleksi::find($id);
        $koleksi->delete();
        return response()->json(['message' => 'Berhasil dihapus']);
    }

    public function koleksiExport()
    {
      $koleksi = Koleksi::select('jurnal', 'tahun', 'jumlah')->get();
      return Excel::create('data_koleksi', function($excel) use ($koleksi) {
        $excel->sheet('mysheet', function($sheet) use ($koleksi) {
            $sheet->fromArray($koleksi);
        });
    })->download('xlsx');

  }

  public function koleksiImport(Request $request){
    if($request->hasFile('file')){
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path, function($reader){})->get();
        if(!empty($data) && $data->count()){
            foreach ($data as $key => $value) {
                $koleksi = new Koleksi();
                $koleksi->jurnal = $value->jurnal;
                $koleksi->tahun = $value->tahun;
                $koleksi->jumlah = $value->jumlah;
                $koleksi->save();
            }
        }
    }
    Alert::success('Upload file berhasil', 'Success')->autoclose(3000);
    return redirect()->back();
}

}
