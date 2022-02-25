<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MysqlModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    /**
     * Needs to backpack
     */
    public string $identifiableAttribute = 'id';

    public static function getPrimaryKey(): string
    {
        return 'id';
    }
}
