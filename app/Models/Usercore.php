<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EDoc\EDocFile;
use App\Models\EDoc\EDocCategory;
use App\Models\EDoc\EDocSubCategory;
use App\Models\Indivinfo;


class Usercore extends Model
{
    use HasFactory;

    protected $table = 'usercore';

    public function indivinfo()
    {
        return $this->hasOne('App\Models\Indivinfo', 'individ', 'indivID');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\it_sec\Permission', 'itsec_permission_user', 'user_id', 'permission_id')->withTimestamps();
    }
    
    public function companyBou()
    {
        return $this->belongsTo('App\Models\CompanyBou', 'bouID', 'bouID');
    }

    public function allowed_categories()
    {
        return $this->belongsToMany(EDocCategory::class, 'edoc_category_user', 'user_indiv_id', 'category_id', 'indivID', 'id')
            ->withTimestamps();
    }

    public function allowed_subcategories()
    {
        return $this->belongsToMany(EDocSubCategory::class, 'edoc_subcategory_user', 'user_indiv_id', 'subcategory_id', 'indivID', 'id')
            ->withTimestamps();
    }

    public function getFullName()
    {
        if($this->indivinfo) {
            return $this->indivinfo->firstName . ' ' . $this->indivinfo->lastName;
        }

        return '';
    }

    public function scpPtos()
    {
        return $this->hasMany('App\Models\dtr\ScpPto', 'indivID', 'indivID');
    }

    public function messages()
    {
        return $this->belongsToMany(MsgMain::class, 'msg2rec', 'recipient_id', 'msg_id')
            ->withPivot('read_at', 'recipient_id', 'msg_id');
    }

    public function rau()
    {
        return $this->belongsToMany(EDocFile::class, 'edoc_rau', 'user_indiv_id', 'edoc_id', 'id', 'id')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
