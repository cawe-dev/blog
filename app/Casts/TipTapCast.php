<?php

namespace App\Casts;

use App\Support\Post\Content;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TipTapCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return new Content([]);
        }

        $data = json_decode($value, true);

        return new Content(is_array($data) ? $data : []);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $data = $value instanceof Content ? $value->toArray() : $value;

        return json_encode($data);
    }
}
