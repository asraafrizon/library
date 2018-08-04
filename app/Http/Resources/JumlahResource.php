<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class JumlahResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'judul_qty' => $this->judul_qty,
        //     'eksemplar_qty' => $this->eksemplar_qty
        // ];
    }
}
