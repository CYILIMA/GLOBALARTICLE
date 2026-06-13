<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advertisement extends Model
{
    protected $fillable = [
        'advertiser_name',
        'banner_title',
        'banner_image',
        'target_url',
        'position',
        'start_date',
        'end_date',
        'status',
        'display_type',
        'price',
        'impressions',
        'clicks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'price'      => 'decimal:2',
    ];

    // ── Enum label maps ────────────────────────────────────────────────────────

    public static function positionLabels(): array
    {
        return [
            'header_leaderboard' => 'Header Leaderboard (Top Banner)',
            'sidebar_rectangle'  => 'Sidebar Rectangle (300×250)',
            'in_article'         => 'In-Article Banner',
            'footer_banner'      => 'Footer Banner',
        ];
    }

    public static function displayTypeLabels(): array
    {
        return [
            'premium'  => 'Premium — Always On',
            'standard' => 'Standard — Rotating',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            'active'    => 'Active',
            'inactive'  => 'Inactive',
            'scheduled' => 'Scheduled',
            'expired'   => 'Expired',
        ];
    }

    // ── Queries ────────────────────────────────────────────────────────────────

    /**
     * Premium ads are shown exclusively. Standard ads only appear when
     * no premium ad is active for the same position.
     */
    public static function getAllActiveForPosition(string $position): \Illuminate\Support\Collection
    {
        $base = static::where('position', $position)
            ->where('status', 'active')
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString());

        $premium = (clone $base)->where('display_type', 'premium')->inRandomOrder()->get();

        return $premium->isNotEmpty()
            ? $premium
            : (clone $base)->where('display_type', 'standard')->inRandomOrder()->get();
    }

    // ── Presentation helpers ───────────────────────────────────────────────────

    public function toDisplayArray(): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->banner_title,
            'image_url' => Storage::url($this->banner_image),
            'click_url' => $this->target_url ? route('ads.click', $this) : null,
            'imp_url'   => route('ads.impression', $this),
        ];
    }

    public function computedStatus(): string
    {
        $today = now()->toDateString();
        if ($this->status === 'inactive') return 'inactive';
        if ($this->end_date->toDateString() < $today) return 'expired';
        if ($this->start_date->toDateString() > $today) return 'scheduled';
        return 'active';
    }
}
