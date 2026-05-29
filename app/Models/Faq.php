<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'm_faq';

    protected $primaryKey = 'faq_id';

    public $timestamps = false; 

    protected $fillable = [
        "faq_id",
        "pertanyaan",
        "jawaban",
        "nomor_urut",
        "dt_record",
        "dt_modified",
        "user_record",
        "user_modified"
    ];

}
