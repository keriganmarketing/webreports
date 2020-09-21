<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
    protected $table = 'companies';

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
