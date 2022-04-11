<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Rule;
use App\Models\Connection;

class Rule extends Model
{
    use HasFactory;

    const STATUS_RUNNING = 'running';
    const STATUS_STOPPED = 'stopped';

    protected $guarded = ['id'];

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
        return ['daily'];
        // return ['daily', 'weekly', 'monthly'];
    }

    public static function scheduleTimes(): array
    {
        return ['random', 'spread'];
        // return ['random', 'exact_time', 'between', 'spread'];
    }

    public function getTotalEmailsCountAttribute()
    {
        return $this->listings->reduce(function ($total, Listing $listing) {
            return $total + (int)$listing->emails()->count();
        }, 0);
    }

    public function getEmailsInPoolCountAttribute()
    {
        return $this->listings->reduce(function ($total, Listing $listing) {
            return $total + (int)$listing->emails()->wherePivot('in_pool', true)->count();
        }, 0);
    }

    public function getActionsPerformedAttribute()
    {
        return $this->actions()->count();
    }

    public function getActionsTotalAttribute()
    {
        return ceil($this->TotalEmailsCount / $this->emails_count);
    }

    public function getActionsLeftAttribute()
    {
        return $this->actionsTotal - $this->actionsPerformed;
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

    public function requiresStage(): bool
    {
        return $this->getAttribute('connection') instanceof Connection ?
            $this->getAttribute('connection')->type === Connection::TYPE_MAUTIC : false;
    }

    public function requiresWebhook(): bool
    {
        return $this->getAttribute('connection') instanceof Connection ?
            $this->getAttribute('connection')->type === Connection::TYPE_WEBHOOK : false;
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(RuleAction::class);
    }

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class);
    }

    public function webhooks(): BelongsToMany
    {
        return $this->belongsToMany(Connection::class);
    }
    
    public function SyncedEmail($id){
        $emails = EmailLogs::where('rule_number',$id)->pluck('email');
        return $emails;
    }
}
