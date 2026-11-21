<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    private const ALLOWED_PAGES = [
        'asal-usul' => 'Asal Usul',
        'profil-seniman' => 'Profil Seniman',
        'alat-musik-oklik' => 'Alat Musik Oklik',
        'soal-evaluasi' => 'Soal Evaluasi',
        'tentang-media' => 'Tentang Media',
    ];

    public function show(string $slug)
    {
        abort_unless(array_key_exists($slug, self::ALLOWED_PAGES), 404);

        $content = DB::table('page_contents')->where('slug', $slug)->first();
        $instruments = null;
        $oklikSettings = null;

        $assetVersioned = function (string $path): string {
            if (str_starts_with($path, 'data:') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            if (str_starts_with($path, '/')) {
                return url($path);
            }

            $normalized = ltrim($path, '/');
            return asset($normalized);
        };

        if ($slug === 'asal-usul') {
            if (! $content || ! is_string($content->body ?? null) || trim((string) $content->body) === '') {
                $slidesBody = json_encode([
                    'assets/slide1.png',
                    'assets/slide2.png',
                    'assets/slide3.png',
                    'assets/slide4.png',
                    'assets/slide5.png',
                ], JSON_UNESCAPED_SLASHES);

                $content = (object) [
                    'title' => $content?->title ?? self::ALLOWED_PAGES[$slug],
                    'body' => $slidesBody,
                ];
            }
        } elseif ($slug === 'alat-musik-oklik') {
            $oklikSettings = [];
            if (is_string($content?->body ?? null) && trim((string) $content->body) !== '') {
                $decoded = json_decode((string) $content->body, true);
                if (is_array($decoded)) {
                    $oklikSettings = [
                        'sim_audio_a' => $decoded['sim_audio_a'] ?? null,
                        'sim_audio_b' => $decoded['sim_audio_b'] ?? null,
                        'sim_audio_c' => $decoded['sim_audio_c'] ?? null,
                        'sim_demo' => $decoded['sim_demo'] ?? null,
                    ];
                }
            }

            $resolveAudio = function (string $name, string $modelPath): ?string {
                $base = pathinfo($modelPath, PATHINFO_FILENAME);
                $lower = strtolower($name . ' ' . $base);

                $candidates = [
                    "assets/{$base}.m4a",
                    'assets/' . strtolower($base) . '.m4a',
                ];

                if (str_contains($lower, 'kerep')) {
                    $candidates[] = 'assets/Kerep.m4a';
                }
                if (str_contains($lower, 'arang')) {
                    $candidates[] = 'assets/Arang.m4a';
                }
                if (str_contains($lower, 'gedhug') || str_contains($lower, 'gedhuh')) {
                    $candidates[] = 'assets/Gedhug.m4a';
                    $candidates[] = 'assets/Demo gedhuh.m4a';
                }

                foreach ($candidates as $path) {
                    if (file_exists(public_path($path))) {
                        return asset($path);
                    }
                }

                return null;
            };

            $seed = [
                [
                    'position' => 1,
                    'name' => 'Klur',
                    'description' => "Klur merupakan bagian dari gedhug, jika tidak ada klur tidak ada gedhug.",
                    'model_path' => 'assets/klur.glb',
                ],
                [
                    'position' => 2,
                    'name' => 'Thintil Kerep',
                    'description' => "Thintil Kerep berperan sebagai pengisi ritme rapat yang membuat pola permainan terdengar lebih “rame” dan hidup.\n\nUmumnya dimainkan pada bagian-bagian tertentu untuk memperkaya dinamika iringan.",
                    'model_path' => 'assets/thintilkerep.glb',
                ],
                [
                    'position' => 3,
                    'name' => 'Thintil Arang',
                    'description' => "Thintil Arang mengisi aksen ritme yang lebih renggang, sehingga memberi ruang pada ketukan dasar dan variasi instrumen lain.\n\nKombinasi pola rapat dan renggang membuat permainan Oklik terdengar seimbang.",
                    'model_path' => 'assets/thintilarang.glb',
                ],
                [
                    'position' => 4,
                    'name' => 'Gedhug',
                    'description' => "Gedhug bisa menghasilkan 3 suara yaitu nada A, B dan C.\n\nSuara yang dihasilkan dari nada A sama dengan bunyi “thak” pada alat musik kendang.\n\nSuara yang dihasilkan dari nada B sama dengan bunyi bass.\n\nSuara yang dihasilkan nada C sama dengan bunyi klur.",
                    'model_path' => 'assets/gedhug.glb',
                ],
            ];

            $instrumentRows = DB::table('instrument_items')
                ->where('page_slug', $slug)
                ->orderBy('position')
                ->get();

            if ($instrumentRows->isNotEmpty()) {
                $instruments = $instrumentRows->map(function ($row) use ($assetVersioned, $resolveAudio) {
                    $modelPath = is_string($row->model_path ?? null) ? trim($row->model_path) : '';
                    $normalizedModelPath = ltrim($modelPath, '/');
                    if (str_ends_with($normalizedModelPath, 'thur.glb')) {
                        $normalizedModelPath = 'assets/klur.glb';
                    }

                    $audioPath = is_string($row->audio_path ?? null) ? trim((string) $row->audio_path) : '';
                    $audioUrl = $audioPath !== '' ? $assetVersioned($audioPath) : $resolveAudio($row->name, $normalizedModelPath);

                    return [
                        'name' => $row->name,
                        'description' => $row->description,
                        'src' => $assetVersioned($normalizedModelPath),
                        'audio' => $audioUrl,
                    ];
                })->values();
            } else {
                $instruments = collect($seed)->map(fn ($row) => [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'src' => $assetVersioned($row['model_path']),
                    'audio' => $resolveAudio($row['name'], $row['model_path']),
                ])->values();
            }
        }

        return view('page', [
            'slug' => $slug,
            'title' => $content?->title ?? self::ALLOWED_PAGES[$slug],
            'body' => $content?->body,
            'instruments' => $instruments,
            'oklikSettings' => $oklikSettings,
        ]);
    }
}

