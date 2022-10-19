<?php

namespace MichaelNabil230\BlockIp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use MichaelNabil230\BlockIp\Events;

class BlockIp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'ip_address',
        'authenticatable_type',
        'authenticatable_id',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => Events\BlockIpSaved::class,
        'created' => Events\BlockIpCreated::class,
    ];

    /**
     * Get the parent of the ip block record.
     */
    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
