<?php

namespace App\Models;

use App\Utils\{CleanField, ConvertDate, ConvertMoney, Document};
use App\Support\Cropper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'genre',
        'document',
        'document_secondary',
        'document_secondary_complement',
        'date_of_birth',
        'place_of_birth',
        'civil_status',
        'cover',
        'occupation',
        'income',
        'company_work',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
        'telephone',
        'cell',
        'type_of_communion',
        'spouse_name',
        'spouse_genre',
        'spouse_document',
        'spouse_document_secondary',
        'spouse_document_secondary_complement',
        'spouse_date_of_birth',
        'spouse_place_of_birth',
        'spouse_occupation',
        'spouse_income',
        'spouse_company_work',
        'lessor',
        'lessee',
        'last_login_at',
        'last_login_ip',
        'admin',
        'client'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function scopeLessors(Builder $query)
    {
        return $query->where('lessor', true);
    }

    public function scopeLessees(Builder $query)
    {
        return $query->where('lessee', true);
    }

    public function setLessorAttribute($value)
    {
        $this->attributes['lessor'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setLesseeAttribute($value)
    {
        $this->attributes['lessee'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function getDocumentAttribute($value)
    {
        return Document::formatBrazilianCPF($value);
    }

    public function setDocumentAttribute($value)
    {
        $this->attributes['document'] = CleanField::clear($value);
    }

    public function getDateOfBirthAttribute($value)
    {
        return ConvertDate::americanToBrazilian($value);
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = ConvertDate::brazilianToAmerican($value);
    }

    public function getIncomeAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setIncomeAttribute($value)
    {
        $this->attributes['income'] = ConvertMoney::brazilianToDouble($value);
    }

    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = CleanField::clear($value);
    }

    public function setTelephoneAttribute($value)
    {
        $this->attributes['telephone'] = CleanField::clear($value);
    }

    public function setCellAttribute($value)
    {
        $this->attributes['cell'] = CleanField::clear($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getSpouseDocumentAttribute($value)
    {
        return Document::formatBrazilianCPF($value);
    }

    public function setSpouseDocumentAttribute($value)
    {
        $this->attributes['spouse_document'] = CleanField::clear($value);
    }

    public function getSpouseDateOfBirthAttribute($value)
    {
        return ConvertDate::americanToBrazilian($value);
    }

    public function setSpouseDateOfBirthAttribute($value)
    {
        $this->attributes['spouse_date_of_birth'] = ConvertDate::brazilianToAmerican($value);
    }

    public function getSpouseIncomeAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setSpouseIncomeAttribute($value)
    {
        $this->attributes['spouse_income'] = ConvertMoney::brazilianToDouble($value);
    }

    public function setAdminAttribute($value)
    {
        $this->attributes['admin'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setClientAttribute($value)
    {
        $this->attributes['client'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function getUrlCoverAttribute()
    {
        if (empty($this->cover)) return '';

        return Storage::url(Cropper::thumb($this->cover, 500, 500));
    }

    public function getLastLoginAtAttribute($value)
    {
        if (empty($value)) return 'N/A';

        return date('d/m/Y', strtotime($value));
    }

    public static function admins(): Collection
    {
        return User::where('admin', 1)->get();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
