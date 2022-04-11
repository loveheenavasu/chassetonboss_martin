<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\GmailConnection;
use App\Models\EventListing;
use App\Models\EventruleAction;
use App\Models\Groups;
use App\Models\GmailConnectionGroup;
use App\Models\EventListingEmail;
use DB;

class Event extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const STATUS_RUNNING = 'running';
    const STATUS_STOPPED = 'stopped';

    protected $table = 'event';
    protected $attributes = [
        'schedule_days' => '["1","2","3","4","5","6","7"]',
        'randomize_emails_order' => false
    ];
    protected $casts = [
        'schedule_days' => 'json',
        'randomize_emails_order' => 'boolean'
    ];

    public static function statuses(): array
    {
        return [self::STATUS_RUNNING, self::STATUS_STOPPED];
    }
    public static function schedules(): array
    {
        return ['daily', 'weekly', 'monthly'];
    }
    public static function scheduleTimes(): array
    {
        return ['random', 'spread'];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(GmailConnection::class);
    }

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(EventListing::class);
    }
    
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Groups::class);
    }

    public function getEstimatedDateAttribute()
    {
        if ($this->schedule === 'daily') {
            return now()->addDays($this->actionsLeft / 1);
        } else if ($this->schedule === 'weekly') {
            return now()->addWeeks($this->actionsLeft);
        } else if ($this->schedule === 'monthly') {
            return now()->addMonths($this->actionsLeft);
        }

        return now();
    }

    public function getActionsLeftAttribute()
    {
        return $this->actionsTotal - $this->actionsPerformed;
    }

    public function getTotalEmailsCountAttribute()
    {
        return $this->listings->reduce(function ($total, EventListing $listing) {
            return $total + (int)$listing->emails()->count();
        }, 0);
    }

    public function getActionsPerformedAttribute()
    {
         $all_lists = DB::table('event_event_listing')
                    ->leftjoin('event as e','e.id','=','event_event_listing.event_id')
                    ->where('event_id',$this->id)
                    ->pluck('event_listing_id')
                    ->toArray();

        $allEmailsInfos = EventListingEmail::whereIn('event_listing_id',$all_lists)
                ->join('eventemails as e','e.id','=','eventlisting_emails.event_email_id')
                ->leftjoin('eventemails_infos as ef','ef.event_email_id','=','e.id')
                ->where('e.sync_status','yes')
                ->select('e.id as email_id','e.email as email','ef.value','ef.type as type','event_listing_id as listing_id','eventlisting_emails.event_email_id as ee_id')
                ->get()->count();

        return number_format((float)$allEmailsInfos/$this->emails_count, 0, '.', '');
        //return $this->actions()->count();
    }

    public function getEmailsInPoolCountAttribute()
    {
        return $this->listings->reduce(function ($total, EventListing $listing) {
            return $total + (int)$listing->emails()->wherePivot('in_pool', true)->count();
        }, 0);
    }

    public function getActionsTotalAttribute()
    {
        return ceil($this->TotalEmailsCount / $this->emails_count);
    }
    

    public function actions(): HasMany
    {
        return $this->hasMany(EventruleAction::class);
    }

    public function requiresSingle(): bool
    {
        return true;
    }

    public function requiresGroup(): bool
    {
        return false;
    }


}