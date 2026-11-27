<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BangunDatarShape extends Model
{
    public const CACHE_KEY_ACTIVE = 'bangun_datar_shapes_list_v2';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'subtype',
        'color',
        'custom_image_path',
        'ciri_ciri',
        'bagian_damar_kurung',
        'urutan',
        'svg_inline',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ciri_ciri' => 'array',
            'urutan' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope query to active shapes only.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query ordered by display urutan then id.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Unique string key used for dropdown values and collaboration data.
     */
    public function getShapeKeyAttribute(): string
    {
        if ($this->subtype !== null && $this->subtype !== '') {
            return "{$this->type}:{$this->subtype}";
        }

        return $this->type;
    }

    /**
     * Load active ordered shapes from cache using primitive arrays so it remains
     * compatible with Laravel's `serializable_classes => false` cache setting.
     *
     * @return Collection<int, self>
     */
    public static function getCachedActive(): Collection
    {
        $rows = Cache::remember(
            self::CACHE_KEY_ACTIVE,
            3600,
            static fn (): array => self::active()
                ->ordered()
                ->get()
                ->map(static fn (self $shape): array => $shape->getAttributes())
                ->all()
        );

        return self::hydrate(is_array($rows) ? $rows : []);
    }

    /**
     * Clear cached bangun datar shape entries.
     */
    public static function clearActiveCache(): void
    {
        Cache::forget(self::CACHE_KEY_ACTIVE);
        Cache::forget('bangun_datar_shapes_active');
    }

    /**
     * Generate a clean default inline SVG (120x100 viewBox) without geometric markers.
     */
    public static function generateDefaultSvg(string $type, ?string $subtype = null): string
    {
        $key = $subtype ? "{$type}:{$subtype}" : $type;

        return match ($key) {
            'persegi' => self::wrapSvg(
                '<rect x="25" y="15" width="70" height="70" rx="2" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
            ),
            'persegi_panjang' => self::wrapSvg(
                '<rect x="12" y="22" width="96" height="56" rx="2" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
            ),
            'lingkaran' => self::wrapSvg(
                '<circle cx="60" cy="50" r="38" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
            ),
            'segitiga:sama_sisi', 'segitiga' => self::buildPolygonSvg([[60, 13], [102, 86], [18, 86]]),
            'segitiga:sama_kaki' => self::buildPolygonSvg([[60, 10], [95, 88], [25, 88]]),
            'segitiga:sembarang' => self::buildPolygonSvg([[42, 14], [106, 78], [14, 86]]),
            'segitiga:lancip' => self::buildPolygonSvg([[52, 14], [100, 82], [20, 86]]),
            'segitiga:siku_siku' => self::buildPolygonSvg([[24, 14], [102, 86], [24, 86]]),
            'segitiga:tumpul' => self::buildPolygonSvg([[12, 24], [108, 84], [46, 84]]),
            'jajar_genjang' => self::buildPolygonSvg([[30, 20], [104, 20], [90, 80], [16, 80]]),
            'layang_layang' => self::buildPolygonSvg([[60, 10], [96, 36], [60, 90], [24, 36]]),
            'belah_ketupat' => self::buildPolygonSvg([[60, 10], [102, 50], [60, 90], [18, 50]]),
            'trapesium:sama_kaki', 'trapesium' => self::buildPolygonSvg([[34, 22], [86, 22], [106, 80], [14, 80]]),
            'trapesium:siku_siku' => self::buildPolygonSvg([[22, 22], [76, 22], [102, 80], [22, 80]]),
            'trapesium:sembarang' => self::buildPolygonSvg([[28, 22], [72, 22], [108, 80], [14, 80]]),
            'trapesium:lancip' => self::buildPolygonSvg([[34, 22], [82, 22], [106, 80], [14, 80]]),
            'trapesium:tumpul' => self::buildPolygonSvg([[14, 24], [62, 24], [108, 80], [44, 80]]),
            'trapesium:tiga_sisi_sama' => self::buildPolygonSvg([[36, 28], [84, 28], [108, 70], [12, 70]]),
            default => self::buildPolygonSvg([[60, 15], [100, 85], [20, 85]]),
        };
    }

    private static function wrapSvg(string $inner): string
    {
        return '<svg viewBox="0 0 120 100" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">'.$inner.'</svg>';
    }

    /**
     * @param  list<array{0: int|float, 1: int|float}>  $points
     */
    private static function buildPolygonSvg(array $points): string
    {
        $pointString = implode(' ', array_map(
            static fn (array $pt): string => "{$pt[0]},{$pt[1]}",
            $points
        ));

        $poly = sprintf(
            '<polygon points="%s" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3" stroke-linejoin="round"/>',
            $pointString
        );

        return self::wrapSvg($poly);
    }
}
