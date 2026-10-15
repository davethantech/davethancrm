<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Company extends Model {
    protected $fillable = ['name','website','industry','email','phone','address','notes','created_by'];
}