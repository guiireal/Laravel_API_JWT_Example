<?php

namespace App\Models;

use App\Utils\ConvertDate;
use App\Utils\ConvertMoney;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'sale',
        'rent',
        'owner_user_id',
        'owner_spouse',
        'owner_company_id_id',
        'acquirer_user_id',
        'acquirer_spouse',
        'acquirer_company_id_id',
        'property_id',
        'sale_price',
        'rent_price',
        'price',
        'tribute',
        'condominium',
        'due_date',
        'deadline_month_month',
        'start_at',
        'status'
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_user_id');
    }

    public function acquirer()
    {
        return $this->hasOne(User::class, 'id', 'acquirer_user_id');
    }

    public function owner_company()
    {
        return $this->hasOne(Company::class, 'id', 'owner_company_id');
    }

    public function acquirer_company()
    {
        return $this->hasOne(Company::class, 'id', 'acquirer_company_id');
    }

    public function scopePendent(Builder $query)
    {
        return $query->where('status', 'pendent');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCanceled(Builder $query)
    {
        return $query->where('status', 'canceled');
    }

    public function property()
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }

    public function setSaleAttribute($value)
    {
        if ($value || $value === 'on') {
            $this->attributes['sale'] = 1;
            $this->attributes['rent'] = 0;
        }
    }

    public function setRentAttribute($value)
    {
        if ($value || $value === 'on') {
            $this->attributes['rent'] = 1;
            $this->attributes['sale'] = 0;
        }
    }

    public function getPriceAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }


    public function setOwnerSpouseAttribute($value)
    {
        $this->attributes['owner_spouse'] = ($value === '1' ? 1 : 0);
    }

    public function setOwnerCompanyIdAtttribute($value)
    {
        $this->attributes['owner_company_id_id'] = ($value == '0' || $value == '' ? null : $value);
    }

    public function setAcquirerSpouseAttribute($value)
    {
        $this->attributes['acquirer_spouse'] = ($value === '1' ? 1 : 0);
    }

    public function setAcquirerCompanyIdAtttribute($value)
    {
        $this->attributes['acquirer_company_id_id'] = ($value == '0' || $value == '' ? null : $value);
    }

    public function setSalePriceAttribute($value)
    {
        if (!empty($value)) $this->attributes['price'] = ConvertMoney::brazilianToDouble($value);
    }

    public function setRentPriceAttribute($value)
    {
        if (!empty($value)) $this->attributes['price'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getTributeAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setTributeAttribute($value)
    {
        if (!empty($value)) $this->attributes['tribute'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getCondominiumAttribute($value)
    {
        return ConvertMoney::doubleToBrazilian($value);
    }

    public function setCondominiumAttribute($value)
    {
        if (!empty($value)) $this->attributes['condominium'] = ConvertMoney::brazilianToDouble($value);
    }

    public function getStartAtAttribute($value)
    {
        return ConvertDate::americanToBrazilian($value);
    }

    public function setStartAtAttribute($value)
    {
        if (!empty($value)) $this->attributes['start_at'] = ConvertDate::brazilianToAmerican($value);
    }

    public function getTermsAttribute()
    {
        // Finalidade [Venda/Locação]
        if ($this->sale == true) {
            $parameters = [
                'purpouse' => 'VENDA',
                'part' => 'VENDEDOR',
                'part_opposite' => 'COMPRADOR',
            ];
        }

        if ($this->rent == true) {
            $parameters = [
                'purpouse' => 'LOCAÇÃO',
                'part' => 'LOCADOR',
                'part_opposite' => 'LOCATÁRIO',
            ];
        }

        $terms[] = "<p style='text-align: center;'>{$this->id} - CONTRATO DE {$parameters['purpouse']} DE IMÓVEL</p>";

        // OWNER
        if (!empty($this->owner_company_id)) { // Se tem empresa

            if (!empty($this->owner_spouse)) { // E tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->owner_company->social_name}</b>, inscrito sob C. N. P. J. nº {$this->owner_company->document_company} e I. E. nº {$this->owner_company->document_company_secondary} exercendo suas atividades no endereço {$this->owner_company->street}, nº {$this->owner_company->number}, {$this->owner_company->complement}, {$this->owner_company->neighborhood}, {$this->owner_company->city}/{$this->owner_company->state}, CEP {$this->owner_company->zipcode} tendo como responsável legal {$this->owner->name}, natural de {$this->owner->place_of_birth}, {$this->owner->civil_status}, {$this->owner->occupation}, portador da cédula de identidade R. G. nº {$this->owner->document_secondary} {$this->owner->document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->document}, e cônjuge {$this->owner->spouse_name}, natural de {$this->owner->spouse_place_of_birth}, {$this->owner->spouse_occupation}, portador da cédula de identidade R. G. nº {$this->owner->spouse_document_secondary} {$this->owner->spouse_document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->spouse_document}, residentes e domiciliados à {$this->owner->street}, nº {$this->owner->number}, {$this->owner->complement}, {$this->owner->neighborhood}, {$this->owner->city}/{$this->owner->state}, CEP {$this->owner->zipcode}.</p>";
            } else { // E não tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->owner_company->social_name}</b>, inscrito sob C. N. P. J. nº {$this->owner_company->document_company} e I. E. nº {$this->owner_company->document_company_secondary} exercendo suas atividades no endereço {$this->owner_company->street}, nº {$this->owner_company->number}, {$this->owner_company->complement}, {$this->owner_company->neighborhood}, {$this->owner_company->city}/{$this->owner_company->state}, CEP {$this->owner_company->zipcode} tendo como responsável legal {$this->owner->name}, natural de {$this->owner->place_of_birth}, {$this->owner->civil_status}, {$this->owner->occupation}, portador da cédula de identidade R. G. nº {$this->owner->document_secondary} {$this->owner->document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->document}, residente e domiciliado à {$this->owner->street}, nº {$this->owner->number}, {$this->owner->complement}, {$this->owner->neighborhood}, {$this->owner->city}/{$this->owner->state}, CEP {$this->owner->zipcode}.</p>";
            }

        } else { // Se não tem empresa

            if (!empty($this->owner_spouse)) { // E tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->owner->name}</b>, natural de {$this->owner->place_of_birth}, {$this->owner->civil_status}, {$this->owner->occupation}, portador da cédula de identidade R. G. nº {$this->owner->document_secondary} {$this->owner->document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->document}, e cônjuge {$this->owner->spouse_name}, natural de {$this->owner->spouse_place_of_birth}, {$this->owner->spouse_occupation}, portador da cédula de identidade R. G. nº {$this->owner->spouse_document_secondary} {$this->owner->spouse_document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->spouse_document}, residentes e domiciliados à {$this->owner->street}, nº {$this->owner->number}, {$this->owner->complement}, {$this->owner->neighborhood}, {$this->owner->city}/{$this->owner->state}, CEP {$this->owner->zipcode}.</p>";
            } else { // E não tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->owner->name}</b>, natural de {$this->owner->place_of_birth}, {$this->owner->civil_status}, {$this->owner->occupation}, portador da cédula de identidade R. G. nº {$this->owner->document_secondary} {$this->owner->document_secondary_complement}, e inscrição no C. P. F. nº {$this->owner->document}, residente e domiciliado à {$this->owner->street}, nº {$this->owner->number}, {$this->owner->complement}, {$this->owner->neighborhood}, {$this->owner->city}/{$this->owner->state}, CEP {$this->owner->zipcode}.</p>";
            }

        }

        // ACQUIRER
        if (!empty($this->acquirer_company_id)) { // Se tem empresa

            if (!empty($this->acquirer_spouse)) { // E tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->acquirer_company->social_name}</b>, inscrito sob C. N. P. J. nº {$this->acquirer_company->document_company} e I. E. nº {$this->acquirer_company->document_company_secondary} exercendo suas atividades no endereço {$this->acquirer_company->street}, nº {$this->acquirer_company->number}, {$this->acquirer_company->complement}, {$this->acquirer_company->neighborhood}, {$this->acquirer_company->city}/{$this->acquirer_company->state}, CEP {$this->acquirer_company->zipcode} tendo como responsável legal {$this->acquirer->name}, natural de {$this->acquirer->place_of_birth}, {$this->acquirer->civil_status}, {$this->acquirer->occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->document_secondary} {$this->acquirer->document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->document}, e cônjuge {$this->acquirer->spouse_name}, natural de {$this->acquirer->spouse_place_of_birth}, {$this->acquirer->spouse_occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->spouse_document_secondary} {$this->acquirer->spouse_document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->spouse_document}, residentes e domiciliados à {$this->acquirer->street}, nº {$this->acquirer->number}, {$this->acquirer->complement}, {$this->acquirer->neighborhood}, {$this->acquirer->city}/{$this->acquirer->state}, CEP {$this->acquirer->zipcode}.</p>";
            } else { // E não tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->acquirer_company->social_name}</b>, inscrito sob C. N. P. J. nº {$this->acquirer_company->document_company} e I. E. nº {$this->acquirer_company->document_company_secondary} exercendo suas atividades no endereço {$this->acquirer_company->street}, nº {$this->acquirer_company->number}, {$this->acquirer_company->complement}, {$this->acquirer_company->neighborhood}, {$this->acquirer_company->city}/{$this->acquirer_company->state}, CEP {$this->acquirer_company->zipcode} tendo como responsável legal {$this->acquirer->name}, natural de {$this->acquirer->place_of_birth}, {$this->acquirer->civil_status}, {$this->acquirer->occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->document_secondary} {$this->acquirer->document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->document}, residente e domiciliado à {$this->acquirer->street}, nº {$this->acquirer->number}, {$this->acquirer->complement}, {$this->acquirer->neighborhood}, {$this->acquirer->city}/{$this->acquirer->state}, CEP {$this->acquirer->zipcode}.</p>";
            }

        } else { // Se não tem empresa

            if (!empty($this->acquirer_spouse)) { // E tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->acquirer->name}</b>, natural de {$this->acquirer->place_of_birth}, {$this->acquirer->civil_status}, {$this->acquirer->occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->document_secondary} {$this->acquirer->document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->document}, e cônjuge {$this->acquirer->spouse_name}, natural de {$this->acquirer->spouse_place_of_birth}, {$this->acquirer->spouse_occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->spouse_document_secondary} {$this->acquirer->spouse_document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->spouse_document}, residentes e domiciliados à {$this->acquirer->street}, nº {$this->acquirer->number}, {$this->acquirer->complement}, {$this->acquirer->neighborhood}, {$this->acquirer->city}/{$this->acquirer->state}, CEP {$this->acquirer->zipcode}.</p>";
            } else { // E não tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->acquirer->name}</b>, natural de {$this->acquirer->place_of_birth}, {$this->acquirer->civil_status}, {$this->acquirer->occupation}, portador da cédula de identidade R. G. nº {$this->acquirer->document_secondary} {$this->acquirer->document_secondary_complement}, e inscrição no C. P. F. nº {$this->acquirer->document}, residente e domiciliado à {$this->acquirer->street}, nº {$this->acquirer->number}, {$this->acquirer->complement}, {$this->acquirer->neighborhood}, {$this->acquirer->city}/{$this->acquirer->state}, CEP {$this->acquirer->zipcode}.</p>";
            }

        }

        $terms[] = "<p style='font-style: italic; font-size: 0.875em;'>A falsidade dessa declaração configura crime previsto no Código Penal Brasileiro, e passível de apuração na forma da Lei.</p>";

        $terms[] = "<p><b>5. IMÓVEL:</b> {$this->property->category_id}, {$this->property->type}, localizada no endereço {$this->property->street}, nº {$this->property->number}, {$this->property->complement}, {$this->property->neighborhood}, {$this->property->city}/{$this->property->state}, CEP {$this->property->zipcode}</p>";

        $terms[] = "<p><b>6. PERÍODO:</b> {$this->deadline_month} meses</p>";

        $terms[] = "<p><b>7. VIGÊNCIA:</b> O presente contrato tem como data de início {$this->start_at} e o término exatamente após a quantidade de meses descrito no item 6 deste.</p>";

        $terms[] = "<p><b>8. VENCIMENTO:</b> Fica estipulado o vencimento no dia {$this->due_date} do mês posterior ao do início de vigência do presente contrato.</p>";

        $terms[] = "<p>Florianópolis, " . date('d/m/Y') . ".</p>";

        $terms[] = "
      <table width='100%' style='margin-top: 50px;'>
         <tr>
            <td>_________________________</td>
            " . ($this->owner_spouse ? "<td>_________________________</td>" : "") . "
         </tr>
         <tr>
            <td>{$parameters['part']}: {$this->owner->name}</td>
            " . ($this->owner_spouse ? "<td>Conjuge: {$this->owner->spouse_name}</td>" : "") . "
         </tr>
         <tr>
            <td>Documento: {$this->owner->document}</td>
            " . ($this->owner_spouse ? "<td>Documento: {$this->owner->spouse_document}</td>" : "") . "
         </tr>
     </table>";


        $terms[] = "
      <table width='100%' style='margin-top: 50px;'>
         <tr>
            <td>_________________________</td>
            " . ($this->acquirer_spouse ? "<td>_________________________</td>" : "") . "
         </tr>
         <tr>
            <td>{$parameters['part_opposite']}: {$this->acquirer->name}</td>
            " . ($this->acquirer_spouse ? "<td>Conjuge: {$this->acquirer->spouse_name}</td>" : "") . "
         </tr>
         <tr>
            <td>Documento: {$this->acquirer->document}</td>
            " . ($this->acquirer_spouse ? "<td>Documento: {$this->acquirer->spouse_document}</td>" : "") . "
         </tr>
      </table>";

        $terms[] = "
      <table width='100%' style='margin-top: 50px;'>
         <tr>
            <td>_________________________</td>
            <td>_________________________</td>
         </tr>
         <tr>
            <td>1ª Testemunha: </td>
            <td>2ª Testemunha: </td>
         </tr>
         <tr>
            <td>Documento: </td>
            <td>Documento: </td>
         </tr>
      </table>";

        return implode('', $terms);
    }
}
