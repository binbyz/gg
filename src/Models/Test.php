<?php

namespace Beaverlabs\Gg\Models;

use Beaverlabs\Gg\Databases\Factories\TestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function newFactory(): TestFactory
    {
        return TestFactory::new();
    }
}
