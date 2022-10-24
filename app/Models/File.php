<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'path',
        'name',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
