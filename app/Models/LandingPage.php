<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\TokenValue;

class LandingPage extends Model
{
    use HasFactory;
    protected $fillable = ['connection_id','landing_template_id','slug','product','affiliate_link','name','custom_code'];
    protected $table = 'landing_page';

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
       
        // $all_tokens = TokenValue::get();
        // $json_data = $all_tokens[0]['name'];
        //$json_pages = json_decode($json_data,true);
        
        //$retuna = Str::of($this->landing_template->content)
            // ->replace('*NAME*', $this->name)
            // ->replace('*PRODUCT*', $this->product);
        // foreach($json_pages as $json_page_k => $json_page_v){
        //    $keys = array_keys($json_page_v);
        //    $values = array_values($json_page_v);
        //    $retuna = $retuna->replace('*'.str_replace('_',' ', $keys[0] ).'*', $values[0]);
        // }
            return $this->landing_template->html;
    }
    public function getStyleAttribute(): string
    {
        return $this->landing_template->css;
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function landing_template(): BelongsTo
    {
        return $this->belongsTo(LandingTemplate::class);
    }

    public function alldata($form){
        return $form;
    }
}
