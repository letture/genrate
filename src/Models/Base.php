<?php


namespace Lettrue\Genrate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Base extends Model
{
    public $timestamps = false;

    public function getTable()
    {
        return $this->table ? $this->table : strtolower(Str::snake(class_basename($this)));
    }
}