<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageConnection extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'landing_paged_connections';
    protected $casts = [
        'was_deployed' => 'boolean'
    ];

    public function getFullUrlAttribute(): string
    {
        if (! $this->landing_page || ! $this->getAttribute('connection')) {
            return '';
        }
        $result=[];
        $profile = Profiles::where('id',$this->landing_page->profile_id)->first();
        if($profile->token_data){
            $url_parm = json_decode($profile->token_data,true);
            $url_parm = $url_parm['token_data'];
            foreach($url_parm as $key => $val){
                $result[] .=  $key . '=' . $val.'&';
            }
            $result = implode($result);
            $final_parms = substr($result,0,-1);
            //echo "<pre>"; print_r(implode($result));die;
            $finall =  trim($this->getAttribute('connection')->base_url, '/') . '/' . $this->landing_page->slug.'/?'.$final_parms;
        
        }else{
            $finall =  trim($this->getAttribute('connection')->base_url, '/') . '/' . $this->landing_page->slug;
        }   
        return $finall;
    }
    public function landing_page(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }
}
