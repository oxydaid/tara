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
     * Generate a clean default inline SVG (120x100 viewBox) with geometric markers
     * where applicable (especially segitiga & trapesium variants).
     */
    public static function generateDefaultSvg(string $type, ?string $subtype = null): string
    {
        $key = $subtype ? "{$type}:{$subtype}" : $type;

        return match ($key) {
            'persegi' => self::wrapSvg(
                '<rect x="25" y="15" width="70" height="70" rx="2" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
                .self::tickMark(25, 15, 95, 15, 1)
                .self::tickMark(95, 15, 95, 85, 1)
                .self::tickMark(95, 85, 25, 85, 1)
                .self::tickMark(25, 85, 25, 15, 1)
                .self::rightAngleBox(25, 85, 25, 15, 95, 85, 11)
            ),
            'persegi_panjang' => self::wrapSvg(
                '<rect x="12" y="22" width="96" height="56" rx="2" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
                .self::tickMark(12, 22, 108, 22, 2)
                .self::tickMark(12, 78, 108, 78, 2)
                .self::tickMark(12, 22, 12, 78, 1)
                .self::tickMark(108, 22, 108, 78, 1)
                .self::rightAngleBox(12, 78, 12, 22, 108, 78, 11)
            ),
            'lingkaran' => self::wrapSvg(
                '<circle cx="60" cy="50" r="38" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3"/>'
            ),
            'segitiga:sama_sisi', 'segitiga' => self::buildPolygonSvg(
                [[60, 13], [102, 86], [18, 86]],
                self::tickMark(60, 13, 102, 86, 1)
                .self::tickMark(102, 86, 18, 86, 1)
                .self::tickMark(18, 86, 60, 13, 1)
                .self::angleArc(60, 13, 18, 86, 102, 86, 14, 1)
                .self::angleArc(102, 86, 60, 13, 18, 86, 14, 1)
                .self::angleArc(18, 86, 102, 86, 60, 13, 14, 1)
            ),
            'segitiga:sama_kaki' => self::buildPolygonSvg(
                [[60, 10], [95, 88], [25, 88]],
                self::tickMark(60, 10, 95, 88, 1)
                .self::tickMark(25, 88, 60, 10, 1)
                .self::angleArc(95, 88, 60, 10, 25, 88, 14, 1)
                .self::angleArc(25, 88, 95, 88, 60, 10, 14, 1)
            ),
            'segitiga:sembarang' => self::buildPolygonSvg(
                [[42, 14], [106, 78], [14, 86]],
                self::tickMark(14, 86, 42, 14, 1)
                .self::tickMark(42, 14, 106, 78, 2)
                .self::tickMark(106, 78, 14, 86, 3)
                .self::angleArc(42, 14, 14, 86, 106, 78, 13, 1)
                .self::angleArc(106, 78, 42, 14, 14, 86, 14, 2)
                .self::angleArc(14, 86, 106, 78, 42, 14, 15, 3)
            ),
            'segitiga:lancip' => self::buildPolygonSvg(
                [[52, 14], [100, 82], [20, 86]],
                self::angleArc(52, 14, 20, 86, 100, 82, 15, 1, '#0F766E')
                .self::angleArc(100, 82, 52, 14, 20, 86, 15, 1, '#0F766E')
                .self::angleArc(20, 86, 100, 82, 52, 14, 15, 1, '#0F766E')
            ),
            'segitiga:siku_siku' => self::buildPolygonSvg(
                [[24, 14], [102, 86], [24, 86]],
                self::rightAngleBox(24, 86, 24, 14, 102, 86, 13)
            ),
            'segitiga:tumpul' => self::buildPolygonSvg(
                [[12, 24], [108, 84], [46, 84]],
                self::angleArc(46, 84, 12, 24, 108, 84, 16, 2, '#991B1B')
            ),
            'jajar_genjang' => self::buildPolygonSvg(
                [[30, 20], [104, 20], [90, 80], [16, 80]],
                self::parallelArrows(30, 20, 104, 20)
                .self::parallelArrows(16, 80, 90, 80)
                .self::tickMark(30, 20, 16, 80, 1)
                .self::tickMark(104, 20, 90, 80, 1)
            ),
            'layang_layang' => self::buildPolygonSvg(
                [[60, 10], [96, 36], [60, 90], [24, 36]],
                self::tickMark(60, 10, 96, 36, 1)
                .self::tickMark(60, 10, 24, 36, 1)
                .self::tickMark(96, 36, 60, 90, 2)
                .self::tickMark(24, 36, 60, 90, 2)
            ),
            'belah_ketupat' => self::buildPolygonSvg(
                [[60, 10], [102, 50], [60, 90], [18, 50]],
                self::tickMark(60, 10, 102, 50, 1)
                .self::tickMark(102, 50, 60, 90, 1)
                .self::tickMark(60, 90, 18, 50, 1)
                .self::tickMark(18, 50, 60, 10, 1)
            ),
            'trapesium:sama_kaki', 'trapesium' => self::buildPolygonSvg(
                [[34, 22], [86, 22], [106, 80], [14, 80]],
                self::parallelArrows(34, 22, 86, 22)
                .self::parallelArrows(14, 80, 106, 80)
                .self::tickMark(34, 22, 14, 80, 1)
                .self::tickMark(86, 22, 106, 80, 1)
                .self::angleArc(14, 80, 34, 22, 106, 80, 14, 1)
                .self::angleArc(106, 80, 86, 22, 14, 80, 14, 1)
                .self::angleArc(34, 22, 86, 22, 14, 80, 13, 2)
                .self::angleArc(86, 22, 106, 80, 34, 22, 13, 2)
            ),
            'trapesium:siku_siku' => self::buildPolygonSvg(
                [[22, 22], [76, 22], [102, 80], [22, 80]],
                self::parallelArrows(22, 22, 76, 22)
                .self::parallelArrows(22, 80, 102, 80)
                .self::rightAngleBox(22, 22, 76, 22, 22, 80, 12)
                .self::rightAngleBox(22, 80, 22, 22, 102, 80, 12)
            ),
            'trapesium:sembarang' => self::buildPolygonSvg(
                [[28, 22], [72, 22], [108, 80], [14, 80]],
                self::parallelArrows(28, 22, 72, 22)
                .self::parallelArrows(14, 80, 108, 80)
                .self::tickMark(28, 22, 14, 80, 1)
                .self::tickMark(72, 22, 108, 80, 2)
                .self::angleArc(14, 80, 28, 22, 108, 80, 13, 1)
                .self::angleArc(108, 80, 72, 22, 14, 80, 14, 2)
            ),
            'trapesium:lancip' => self::buildPolygonSvg(
                [[34, 22], [82, 22], [106, 80], [14, 80]],
                self::parallelArrows(34, 22, 82, 22)
                .self::parallelArrows(14, 80, 106, 80)
                .self::angleArc(14, 80, 34, 22, 106, 80, 16, 1, '#0F766E')
                .self::angleArc(106, 80, 82, 22, 14, 80, 16, 1, '#0F766E')
            ),
            'trapesium:tumpul' => self::buildPolygonSvg(
                [[14, 24], [62, 24], [108, 80], [44, 80]],
                self::parallelArrows(14, 24, 62, 24)
                .self::parallelArrows(44, 80, 108, 80)
                .self::angleArc(44, 80, 14, 24, 108, 80, 16, 2, '#991B1B')
                .self::angleArc(62, 24, 14, 24, 108, 80, 16, 2, '#991B1B')
            ),
            'trapesium:tiga_sisi_sama' => self::buildPolygonSvg(
                [[36, 28], [84, 28], [108, 70], [12, 70]],
                self::parallelArrows(36, 28, 84, 28)
                .self::parallelArrows(12, 70, 108, 70)
                .self::tickMark(36, 28, 84, 28, 1)
                .self::tickMark(36, 28, 12, 70, 1)
                .self::tickMark(84, 28, 108, 70, 1)
            ),
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
    private static function buildPolygonSvg(array $points, string $markers = ''): string
    {
        $pointString = implode(' ', array_map(
            static fn (array $pt): string => "{$pt[0]},{$pt[1]}",
            $points
        ));

        $poly = sprintf(
            '<polygon points="%s" fill="currentColor" fill-opacity="0.88" stroke="#3D1804" stroke-width="3" stroke-linejoin="round"/>',
            $pointString
        );

        return self::wrapSvg($poly.$markers);
    }

    private static function tickMark(float $x1, float $y1, float $x2, float $y2, int $count = 1, string $stroke = '#1E293B'): string
    {
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $len = hypot($dx, $dy);
        if ($len < 1e-6) {
            return '';
        }

        $ux = $dx / $len;
        $uy = $dy / $len;
        $nx = -$uy;
        $ny = $ux;

        $mx = ($x1 + $x2) / 2.0;
        $my = ($y1 + $y2) / 2.0;
        $halfTick = 5.5;
        $spacing = 4.5;
        $lines = '';

        for ($i = 0; $i < $count; $i++) {
            $offset = ($i - ($count - 1) / 2.0) * $spacing;
            $cx = $mx + $ux * $offset;
            $cy = $my + $uy * $offset;
            $tx1 = round($cx - $nx * $halfTick, 1);
            $ty1 = round($cy - $ny * $halfTick, 1);
            $tx2 = round($cx + $nx * $halfTick, 1);
            $ty2 = round($cy + $ny * $halfTick, 1);
            $lines .= sprintf(
                '<line x1="%s" y1="%s" x2="%s" y2="%s" stroke="%s" stroke-width="2.2" stroke-linecap="round"/>',
                $tx1,
                $ty1,
                $tx2,
                $ty2,
                $stroke
            );
        }

        return $lines;
    }

    private static function angleArc(
        float $vx,
        float $vy,
        float $ax,
        float $ay,
        float $bx,
        float $by,
        float $radius = 14.0,
        int $rings = 1,
        string $stroke = '#1E293B'
    ): string {
        $d1 = hypot($ax - $vx, $ay - $vy);
        $d2 = hypot($bx - $vx, $by - $vy);
        if ($d1 < 1e-6 || $d2 < 1e-6) {
            return '';
        }

        $u1x = ($ax - $vx) / $d1;
        $u1y = ($ay - $vy) / $d1;
        $u2x = ($bx - $vx) / $d2;
        $u2y = ($by - $vy) / $d2;

        $cross = $u1x * $u2y - $u1y * $u2x;
        $sweep = $cross >= 0 ? 1 : 0;

        $paths = '';
        for ($i = 0; $i < $rings; $i++) {
            $r = $radius + ($i * 4.0);
            $sx = round($vx + $u1x * $r, 1);
            $sy = round($vy + $u1y * $r, 1);
            $ex = round($vx + $u2x * $r, 1);
            $ey = round($vy + $u2y * $r, 1);
            $paths .= sprintf(
                '<path d="M %s %s A %s %s 0 0 %d %s %s" fill="none" stroke="%s" stroke-width="2" stroke-linecap="round"/>',
                $sx,
                $sy,
                $r,
                $r,
                $sweep,
                $ex,
                $ey,
                $stroke
            );
        }

        return $paths;
    }

    private static function rightAngleBox(
        float $vx,
        float $vy,
        float $ax,
        float $ay,
        float $bx,
        float $by,
        float $size = 12.0,
        string $stroke = '#1E293B'
    ): string {
        $d1 = hypot($ax - $vx, $ay - $vy);
        $d2 = hypot($bx - $vx, $by - $vy);
        if ($d1 < 1e-6 || $d2 < 1e-6) {
            return '';
        }

        $u1x = ($ax - $vx) / $d1;
        $u1y = ($ay - $vy) / $d1;
        $u2x = ($bx - $vx) / $d2;
        $u2y = ($by - $vy) / $d2;

        $p1x = round($vx + $u1x * $size, 1);
        $p1y = round($vy + $u1y * $size, 1);
        $p2x = round($vx + ($u1x + $u2x) * $size, 1);
        $p2y = round($vy + ($u1y + $u2y) * $size, 1);
        $p3x = round($vx + $u2x * $size, 1);
        $p3y = round($vy + $u2y * $size, 1);

        return sprintf(
            '<polyline points="%s,%s %s,%s %s,%s" fill="none" stroke="%s" stroke-width="2.2" stroke-linejoin="miter"/>',
            $p1x,
            $p1y,
            $p2x,
            $p2y,
            $p3x,
            $p3y,
            $stroke
        );
    }

    private static function parallelArrows(float $x1, float $y1, float $x2, float $y2, string $stroke = '#1E293B'): string
    {
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $len = hypot($dx, $dy);
        if ($len < 1e-6) {
            return '';
        }

        $ux = $dx / $len;
        $uy = $dy / $len;
        $nx = -$uy;
        $ny = $ux;

        $mx = ($x1 + $x2) / 2.0;
        $my = ($y1 + $y2) / 2.0;

        $tipX = round($mx + $ux * 3.5, 1);
        $tipY = round($my + $uy * 3.5, 1);
        $arm1X = round($mx - $ux * 3.5 + $nx * 4.5, 1);
        $arm1Y = round($my - $uy * 3.5 + $ny * 4.5, 1);
        $arm2X = round($mx - $ux * 3.5 - $nx * 4.5, 1);
        $arm2Y = round($my - $uy * 3.5 - $ny * 4.5, 1);

        return sprintf(
            '<polyline points="%s,%s %s,%s %s,%s" fill="none" stroke="%s" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
            $arm1X,
            $arm1Y,
            $tipX,
            $tipY,
            $arm2X,
            $arm2Y,
            $stroke
        );
    }
}
