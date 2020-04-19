<?php

namespace App\Models;

use App\Support\Cropper;
use App\Utils\ConvertMoney;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    protected $fillable = [
        'sale',
        'rent',
        'category',
        'type',
        'user_id',
        'sale_price',
        'rent_price',
        'tribute',
        'condominium',
        'description',
        'bedrooms',
        'suites',
        'bathrooms',
        'rooms',
        'garage',
        'garage_covered',
        'area_total',
        'area_util',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
        'air_conditioning',
        'bar',
        'library',
        'barbecue_grill',
        'american_kitchen',
        'fitted_kitchen',
        'pantry',
        'edicule',
        'office',
        'bathtub',
        'fireplace',
        'lavatory',
        'furnished',
        'pool',
        'steam_room',
        'view_of_the_sea',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)
            ->orderByDesc('cover');
    }

    public function getCoverAttribute()
    {
        $images = $this->images();

        $cover = $images->where('cover', 1)->first(['path']);

        if (!$cover) $cover = $images->first(['path']);

        if (empty($cover['path']) || !File::exists("../public/storage/{$cover['path']}"))
            return asset('backend/assets/images/realty.jpeg');

        return Storage::url(Cropper::thumb($cover['path'], 1366, 768));
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable(Builder $query)
    {
        return $query->where('status', 0);
    }

    public function getTranslateCategoryAttribute()
    {
        switch ($this->category) {
            case 'residential_property': return 'Imóvel Residencial';
            case 'commercial_industrial': return 'Comercial/Industrial';
            case 'terrain': return 'Terreno';
            default: return '';
        }
    }

    public function getTranslateTypeAttribute()
    {
        switch ($this->type) {
            case 'home': return 'Casa';
            case 'roof': return 'Cobertura';
            case 'apartment': return 'Apartamento';
            case 'studio': return 'Studio';
            case 'kitnet': return 'Kitnet';
            case 'commercial_room': return 'Sala Comercial';
            case 'deposit_shed': return 'Depósito/Galpão';
            case 'commercial_point': return 'Ponto Comercial';
            case 'terrain': return 'Terreno';
            default: return '';
        }
    }

    public function setSaleAttribute($value)
    {
        $this->attributes['sale'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setRentAttribute($value)
    {
        $this->attributes['rent'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? true : false);
    }

    public function getStatusAttribute($value)
    {
        return ($value == 1 ? true : false);
    }

    public function getSalePriceAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getRentPriceAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setRentPriceAttribute($value)
    {
        $this->attributes['rent_price'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getTributeAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setTributeAttribute($value)
    {
        $this->attributes['tribute'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getCondominiumAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setCondominiumAttribute($value)
    {
        $this->attributes['condominium'] = ConvertMoney::brazilianToDouble($value);
    }

    public function setAirConditioningAttribute($value)
    {
        $this->attributes['air_conditioning'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setBarAttribute($value)
    {
        $this->attributes['bar'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setLibraryAttribute($value)
    {
        $this->attributes['library'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setBarbecueGrillAttribute($value)
    {
        $this->attributes['barbecue_grill'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setAmericanKitchenAttribute($value)
    {
        $this->attributes['american_kitchen'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setFittedKitchenAttribute($value)
    {
        $this->attributes['fitted_kitchen'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setPantryAttribute($value)
    {
        $this->attributes['pantry'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setEdiculeAttribute($value)
    {
        $this->attributes['edicule'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setOfficeAttribute($value)
    {
        $this->attributes['office'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setBathtubAttribute($value)
    {
        $this->attributes['bathtub'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setFirePlaceAttribute($value)
    {
        $this->attributes['fireplace'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setLavatoryAttribute($value)
    {
        $this->attributes['lavatory'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setFurnishedAttribute($value)
    {
        $this->attributes['furnished'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setPoolAttribute($value)
    {
        $this->attributes['pool'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setSteamRoomAttribute($value)
    {
        $this->attributes['steam_room'] = isset($value) && $value == 'on' ? 1 : 0;
    }

    public function setViewOfTheSeaAttribute($value)
    {
        $this->attributes['view_of_the_sea'] = isset($value) && $value == 'on' ? 1 : 0;
    }
}
