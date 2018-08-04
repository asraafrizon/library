<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LayananResource extends Resource
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
            'aktivitas' => $this->aktivitas,
            'tahun' => $this->tahun
        ];
    }
}
