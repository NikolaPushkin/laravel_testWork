<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    protected $table = 'relations';

    public $timestamps = false;
    protected $fillable = ['parent_objid', 'begda', 'endda', 'objid', 'npp'];

    public function relations()
    {
        return $this->hasMany(Relation::class, 'parent_objid', 'objid');
    }

    public function scopeRelationExists($query, $datePeriodBegin, $datePeriodEnd)
    {
        return $query->where('relations.endda', '>=', $datePeriodBegin)
            ->where('relations.begda', '<=', $datePeriodEnd);
    }
}
