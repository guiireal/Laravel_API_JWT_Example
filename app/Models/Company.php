<?php

namespace App\Models;

use App\Utils\CleanField;
use App\Utils\Document;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getDocumentCompanyAttribute($value)
    {
        return Document::formatBrazilianCNPJ($value);
    }

    public function setDocumentCompanyAttribute($value)
    {
        $this->attributes['document_company'] = CleanField::clear($value);
    }
}
