<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    public const WEEK_DAYS = [
        'mon' => ['uz' => 'Dush', 'schema' => 'Monday'],
        'tue' => ['uz' => 'Sesh', 'schema' => 'Tuesday'],
        'wed' => ['uz' => 'Chor', 'schema' => 'Wednesday'],
        'thu' => ['uz' => 'Pay',  'schema' => 'Thursday'],
        'fri' => ['uz' => 'Juma', 'schema' => 'Friday'],
        'sat' => ['uz' => 'Shan', 'schema' => 'Saturday'],
        'sun' => ['uz' => 'Yak',  'schema' => 'Sunday'],
    ];

    protected $fillable = [
        'phone',
        'telegram',
        'whatsapp',
        'email',
        'address',
        'work_days',
        'work_time_from',
        'work_time_to',
        'google_analytics_id',
        'yandex_metrica_id',
    ];

    protected $casts = [
        'work_days' => 'array',
    ];

    public static function current(): self
    {
        return Cache::rememberForever('site_settings', fn() => static::query()->firstOrCreate(['id' => 1]));
    }

    public static function forgetCache(): void
    {
        Cache::forget('site_settings');
    }

    public function getTelegramUrlAttribute(): ?string
    {
        return $this->telegram ? 'https://t.me/' . ltrim($this->telegram, '@') : null;
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        return $this->whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $this->whatsapp) : null;
    }

    /** Human-readable "Dush–Juma 9:00–18:00" style summary, grouping consecutive selected days. */
    public function getWorkTimeDisplayAttribute(): ?string
    {
        $days = $this->workDaysLabel();

        if (!$days) {
            return null;
        }

        if (!$this->work_time_from || !$this->work_time_to) {
            return $days;
        }

        return "{$days} {$this->work_time_from}–{$this->work_time_to}";
    }

    /** Selected days mapped to schema.org day names, in week order — for openingHoursSpecification. */
    public function getWorkDaysSchemaAttribute(): array
    {
        $order = array_keys(self::WEEK_DAYS);

        return array_map(
            fn($day) => self::WEEK_DAYS[$day]['schema'],
            array_values(array_intersect($order, $this->work_days ?? []))
        );
    }

    private function workDaysLabel(): ?string
    {
        $order = array_keys(self::WEEK_DAYS);
        $selected = array_values(array_intersect($order, $this->work_days ?? []));

        if (empty($selected)) {
            return null;
        }

        $ranges = [];
        $start = $prev = array_search($selected[0], $order);

        foreach (array_slice($selected, 1) as $day) {
            $idx = array_search($day, $order);
            if ($idx === $prev + 1) {
                $prev = $idx;
                continue;
            }
            $ranges[] = [$start, $prev];
            $start = $prev = $idx;
        }
        $ranges[] = [$start, $prev];

        $labels = array_map(function ($range) use ($order) {
            [$start, $end] = $range;
            $startLabel = self::WEEK_DAYS[$order[$start]]['uz'];

            return $start === $end
                ? $startLabel
                : $startLabel . '–' . self::WEEK_DAYS[$order[$end]]['uz'];
        }, $ranges);

        return implode(', ', $labels);
    }
}
