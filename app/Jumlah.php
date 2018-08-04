<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jumlah extends Model
{
	protected $table = 'jumlah';
	
	protected $fillable = [
		'id', 'judul_qty', 'eksemplar_qty'
	];
}
