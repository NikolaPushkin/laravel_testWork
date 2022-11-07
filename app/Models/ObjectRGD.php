<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectRGD extends Model

{
    use HasFactory;

    protected $table = 'objects';

    public $timestamps = false;
    protected $fillable = ['objid', 'begda', 'endda', 'stext'];

    public function relations()
    {
        return $this->hasMany(Relation::class, 'parent_objid', 'objid');
    }

    public function scopeObjectExists($query, $datePeriodBegin,$datePeriodEnd)
    {
        return $query->where('objects.endda', '>=', $datePeriodBegin)
            ->where('objects.begda', '<=', $datePeriodEnd);
    }
}
