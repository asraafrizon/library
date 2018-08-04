<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Koleksi;
use App\Inventori;
use App\Layanan;
use App\Http\Resources\Koleksi as KoleksiResource;
use DB;


class dashboardController extends Controller
{
	public function koleksi()
	{
		// $jurnal = DB::table('koleksis')->select('jurnal', DB::raw('sum(jumlah) as total'))->groupBy('jurnal')->get();
		// $tahun = DB::table('koleksis')->select('tahun', DB::raw('sum(jumlah) as total'))->groupBy('tahun')->get();

		$koleksi = Koleksi::all();
		return response()->json($koleksi);
	}

	public function inventori()
	{
		// $jurnal = DB::table('koleksis')->select('jurnal', DB::raw('sum(jumlah) as total'))->groupBy('jurnal')->get();
		// $tahun = DB::table('koleksis')->select('tahun', DB::raw('sum(jumlah) as total'))->groupBy('tahun')->get();

		$inventori = Inventori::all();
		return response()->json($inventori);
	}

	public function layanan()
	{
		// $jurnal = DB::table('koleksis')->select('jurnal', DB::raw('sum(jumlah) as total'))->groupBy('jurnal')->get();
		// $tahun = DB::table('koleksis')->select('tahun', DB::raw('sum(jumlah) as total'))->groupBy('tahun')->get();

		$layanan = Layanan::all();
		return response()->json($layanan);
	}
}
