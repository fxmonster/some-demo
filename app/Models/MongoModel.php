<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class MongoModel extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $primaryKey = '_id';

    protected $keyType = 'string';

    protected $dates = [];

    protected $casts = [];

    public static function getPrimaryKey(): string
    {
        return '_id';
    }
}
