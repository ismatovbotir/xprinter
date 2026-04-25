<?php

namespace App\Livewire\Admin;

use App\Models\Region;
use Livewire\Component;

class RegionCascade extends Component
{
    public ?int $countryId  = null;
    public ?int $regionId   = null;
    public array $regions   = [];

    public function mount(?int $countryId = null, ?int $regionId = null): void
    {
        $this->countryId = $countryId;
        $this->regionId  = $regionId;

        if ($countryId) {
            $this->loadRegions();
        }
    }

    public function updatedCountryId(): void
    {
        $this->regionId = null;
        $this->loadRegions();
    }

    private function loadRegions(): void
    {
        $this->regions = Region::with('translations')
            ->where('country_id', $this->countryId)
            ->get()
            ->map(fn($r) => [
                'id'   => $r->id,
                'name' => $r->translations->where('lang', app()->getLocale())->first()?->name
                       ?? $r->translations->first()?->name
                       ?? "Region #{$r->id}",
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.region-cascade');
    }
}
