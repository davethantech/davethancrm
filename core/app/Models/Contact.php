<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Contact extends Model {
    protected $fillable = ['first_name','last_name','email','phone','job_title','company_id','notes','created_by'];
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
}