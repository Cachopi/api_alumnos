<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AlumnoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    //agrego el metodo se ejecuta despues del array del resource
    public function with(Request $request)
    {
        return ["jsonapi" =>
            ["version" => "1.0"]];
    }
}
