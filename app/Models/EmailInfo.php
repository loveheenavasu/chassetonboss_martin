<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailInfo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function types(): array
    {
        return array_keys(static::typeOptions());
    }

    public function getLabelAttribute(): string
    {
        return static::typeOptions()[$this->type] ?? '';
    }

    public static function typeOptions(): array
    {
        $list = [
            'First Name',
            'Last Name',
            'Position',
            'Mobile',
            'Phone',
            'Address Line 1',
            'Address Line 2',
            'City',
            'State',
            'Zip Code',
            'Country',
            'Website',
            'Facebook',
            'Instagram',
            'Profession',
            'Google+',
            'Company Name',
            'Number Of Employees',
            'Industry',
            'Date Created',
            'Skype',
            'Annual Revenue',
        ];

        return collect($list)->mapWithKeys(function ($item) {
            return [Str::snake($item) => $item];
        })->toArray();
    }
}
