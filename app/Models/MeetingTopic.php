<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingTopic extends Model
{
    use HasFactory;

    protected $table = 'meeting_topics';

    protected $primaryKey = 'topic_id';

    public $timestamps = false;
}
