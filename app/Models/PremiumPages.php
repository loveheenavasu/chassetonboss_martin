<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use DB;

class PremiumPages extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getFullUrlAttribute(): string
    {
        $connection = $this->getAttribute('connection');
        if (!$connection) {
            return '';
        }

         return rtrim($connection->base_url, '/') . '/' . $this->slug;
    }

    public function getTitleAttribute(): string
    {
        return '';
    }

    public function getContentAttribute(): string
    {
        return Str::of($this->PremiumTemplates->content)
            ->replace('*PRODUCT NAME*', $this->product_name)
            ->replace('*AFFLIATE LINK*', $this->affiliate_link)
            ->replace('src="https://referer.commissionbanjar.com/product_img21632464619.png"', 'src="'. asset('/storage').'/'.$this->hero_image.'"')
            ->replace('*HEADER TEXT*', $this->header_text)
            ->replace('*HERO TEXT1*', $this->hero_text1)
            ->replace('*HERO TEXT2*', $this->hero_text2)
            ->replace('*BUTTON TEXT*', $this->PremiumTemplates->button_text)
            ->replace('*PRODUCT HEADER*', $this->product_header)
            ->replace('src="https://referer.commissionbanjar.com/storage/product_img11632490864.jpg"','src="'. asset('/storage').'/'.$this->product_image1.'"')
            ->replace('src="https://referer.commissionbanjar.com/storage/product_img31632542494.jpg"','src="'. asset('/storage').'/'.$this->product_image2.'"')
            ->replace('src="https://referer.commissionbanjar.com/storage/product_img11632489805.png"','src="'. asset('/storage').'/'.$this->product_image3.'"')
            ->replace('*PRODUCT IMAGE1 LINK*', $this->product_image1_link)
            ->replace('*PRODUCT IMAGE2 LINK*', $this->product_image2_link)
            ->replace('*PRODUCT IMAGE3 LINK*', $this->product_image3_link);

    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function PremiumTemplates()
    {

        return $this->belongsTo(PremiumTemplates::class);
    }
}
