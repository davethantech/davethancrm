<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
class Lead extends Model {
    protected $fillable = ['name','company','email','phone','source','status','value','notes','created_by'];
    protected $casts = ['value'=>'decimal:2'];
}