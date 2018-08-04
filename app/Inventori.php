<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventori extends Model
{
	protected $table = 'inventoris';
	

	protected $fillable = [
		'id', 'tahun', 'fakultas', 'judul_qty', 'eksemplar_qty'
	];

	
	// public function fakultas()
	// {
	// 	return $this->belongsTo(Fakultas::class);

	// }

	// public function jumlah()
	// {
	// 	return $this->belongsTo(Jumlah::class, 'qty_id');

	// }

}
