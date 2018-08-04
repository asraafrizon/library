<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventori;
use App\Http\Resources\InventoriResource;
use Excel;
use DB;
use Alert;

class InventoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $data = Inventori::all();       
        // Return response()->json(['data' => $data]);
        $inventori = Inventori::paginate('15');
        return InventoriResource::collection($inventori);
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
            'tahun' => 'required',
            'fakultas' => 'required',
            'judul_qty' => 'required',
            'eksemplar_qty' => 'required'
        ]);

        $inventori = new Inventori;
        $inventori->tahun = $request->tahun;
        $inventori->fakultas = $request->fakultas;
        $inventori->judul_qty = $request->judul_qty;
        $inventori->eksemplar_qty = $request->eksemplar_qty;
        $inventori->save();

        return response()->json(['inventori' => Inventori::find($inventori->id)]);
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
            'tahun' => 'required',
            'fakultas' => 'required',
            'judul_qty' => 'required',
            'eksemplar_qty' => 'required'
        ]);

        $inventori = Inventori::find($id);
        $inventori->tahun = $request->tahun;
        $inventori->fakultas = $request->fakultas;
        $inventori->judul_qty = $request->judul_qty;
        $inventori->eksemplar_qty = $request->eksemplar_qty;
        $inventori->save();

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
        $inventori = Inventori::find($id);
        $inventori->delete();
        return response()->json(['message' => 'Berhasil dihapus']);
    }

    public function inventoriExport()
    {
      $inventori = Inventori::select('tahun', 'fakultas', 'judul_qty','eksemplar_qty')->get();
      return Excel::create('data_inventori', function($excel) use ($inventori) {
        $excel->sheet('mysheet', function($sheet) use ($inventori) {
            $sheet->fromArray($inventori);
        });
    })->download('xlsx');

  }

  public function inventoriImport(Request $request)
  {
    if($request->hasFile('file'))
    {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path, function($reader){})->get();
        if(!empty($data) && $data->count())
        {
            foreach ($data as $key => $value) {
                $inventori = new Inventori();
                $inventori->tahun = $value->tahun;
                $inventori->fakultas = $value->fakultas;
                $inventori->judul_qty = $value->judul_qty;
                $inventori->eksemplar_qty = $value->eksemplar_qty;
                $inventori->save();
            }
        }
    }
    Alert::success('Upload file berhasil', 'Success')->autoclose(3000);
    return view('inventori');
}

}
