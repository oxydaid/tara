<?php

use App\Models\BangunDatarShape;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('bangun_datar_shapes')) {
            return;
        }

        BangunDatarShape::query()->each(function (BangunDatarShape $shape): void {
            $dirty = false;

            if (empty($shape->custom_image_path)) {
                $shape->svg_inline = BangunDatarShape::generateDefaultSvg($shape->type, $shape->subtype);
                $dirty = true;
            }

            if (is_array($shape->ciri_ciri)) {
                $updatedCiri = array_map(static function (string $item): string {
                    return str_replace(
                        [' (ditandai 1 garis sama panjang)', ', bertanda kotak'],
                        ['', ''],
                        $item
                    );
                }, $shape->ciri_ciri);

                if ($updatedCiri !== $shape->ciri_ciri) {
                    $shape->ciri_ciri = $updatedCiri;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $shape->save();
            }
        });

        BangunDatarShape::clearActiveCache();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
