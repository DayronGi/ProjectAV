<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAssistant extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'meeting_assistants';

    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'status'
    ];
}