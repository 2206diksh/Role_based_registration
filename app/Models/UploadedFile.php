<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    use HasFactory;

    protected $table = 'uploaded_files'; // explicitly specifying the table name

    protected $fillable = [
        'original_name',
        'path',
    ];
}
