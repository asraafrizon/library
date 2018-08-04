<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
// use App\Http\Resources\Fakultas as FakultasResource;
// use App\Http\Resources\Jumlah as JumlahResource;

class InventoriResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       return [
            'id' => $this->id,
            'tahun' => $this->tahun,
            'fakultas' =>  $this->fakultas,
            'judul_qty' => $this->judul_qty,
            'eksemplar_qty' => $this->eksemplar_qty
        ];
    }
}
