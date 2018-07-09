<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 08-07-2018
 * Time: 15:18
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class IdName extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['id' => $this->id, 'name' => $this->name ];
    }
}