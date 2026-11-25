<?php

namespace App\Http\Controllers\Admin;

use App\Models\BangunDatarShape;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBangunDatarController extends AdminController
{
    /**
     * @var array<string, string>
     */
    private const SHAPE_TYPES = [
        'persegi' => 'Persegi',
        'persegi_panjang' => 'Persegi Panjang',
        'lingkaran' => 'Lingkaran',
        'segitiga' => 'Segitiga',
        'jajar_genjang' => 'Jajar Genjang',
        'layang_layang' => 'Layang-Layang',
        'belah_ketupat' => 'Belah Ketupat',
        'trapesium' => 'Trapesium',
    ];

    public function index(): View
    {
        $shapes = BangunDatarShape::ordered()->paginate(12);

        return view('admin.bangun-datar.index', compact('shapes'));
    }

    public function create(): View
    {
        $nextOrder = (int) (BangunDatarShape::max('urutan') ?? 0) + 1;

        return view('admin.bangun-datar.form', [
            'shape' => new BangunDatarShape([
                'type' => 'persegi',
                'color' => '#E57354',
                'urutan' => $nextOrder,
                'is_active' => true,
                'ciri_ciri' => [''],
            ]),
            'isEdit' => false,
            'shapeTypes' => self::SHAPE_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateShape($request);
        $ciriList = $this->cleanCiriList($request);

        if ($ciriList === []) {
            return back()
                ->withInput()
                ->withErrors(['ciri_ciri' => 'Minimal harus ada 1 ciri-ciri bangun datar.']);
        }

        $customImagePath = null;
        if ($request->hasFile('custom_image')) {
            $customImagePath = $this->storeCustomImage($request);
        }

        BangunDatarShape::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'subtype' => $validated['subtype'] ?? null,
            'color' => $validated['color'],
            'custom_image_path' => $customImagePath,
            'ciri_ciri' => $ciriList,
            'bagian_damar_kurung' => $validated['bagian_damar_kurung'] ?: null,
            'urutan' => $validated['urutan'],
            'svg_inline' => BangunDatarShape::generateDefaultSvg($validated['type'], $validated['subtype'] ?? null),
            'is_active' => $validated['is_active'],
        ]);

        BangunDatarShape::clearActiveCache();

        return redirect()
            ->route('admin.bangun-datar.index')
            ->with('status', 'Data bangun datar berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $shape = BangunDatarShape::findOrFail($id);

        return view('admin.bangun-datar.form', [
            'shape' => $shape,
            'isEdit' => true,
            'shapeTypes' => self::SHAPE_TYPES,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $shape = BangunDatarShape::findOrFail($id);
        $validated = $this->validateShape($request);
        $ciriList = $this->cleanCiriList($request);

        if ($ciriList === []) {
            return back()
                ->withInput()
                ->withErrors(['ciri_ciri' => 'Minimal harus ada 1 ciri-ciri bangun datar.']);
        }

        $customImagePath = $shape->custom_image_path;

        if ($request->boolean('remove_custom_image')) {
            $this->deleteCustomImage($customImagePath);
            $customImagePath = null;
        }

        if ($request->hasFile('custom_image')) {
            $this->deleteCustomImage($customImagePath);
            $customImagePath = $this->storeCustomImage($request);
        }

        $typeChanged = $shape->type !== $validated['type'] || $shape->subtype !== ($validated['subtype'] ?? null);

        $shape->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'subtype' => $validated['subtype'] ?? null,
            'color' => $validated['color'],
            'custom_image_path' => $customImagePath,
            'ciri_ciri' => $ciriList,
            'bagian_damar_kurung' => $validated['bagian_damar_kurung'] ?: null,
            'urutan' => $validated['urutan'],
            'svg_inline' => ($typeChanged || empty($shape->svg_inline))
                ? BangunDatarShape::generateDefaultSvg($validated['type'], $validated['subtype'] ?? null)
                : $shape->svg_inline,
            'is_active' => $validated['is_active'],
        ]);

        BangunDatarShape::clearActiveCache();

        return redirect()
            ->route('admin.bangun-datar.index')
            ->with('status', 'Data bangun datar berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $shape = BangunDatarShape::findOrFail($id);
        $this->deleteCustomImage($shape->custom_image_path);
        $shape->delete();

        BangunDatarShape::clearActiveCache();

        return redirect()
            ->route('admin.bangun-datar.index')
            ->with('status', 'Data bangun datar berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateShape(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:60'],
            'subtype' => ['nullable', 'string', 'max:60'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'custom_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'ciri_ciri' => ['required', 'array', 'min:1'],
            'ciri_ciri.*' => ['nullable', 'string', 'max:255'],
            'bagian_damar_kurung' => ['nullable', 'string', 'max:120'],
            'urutan' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    /**
     * @return list<string>
     */
    private function cleanCiriList(Request $request): array
    {
        $raw = $request->input('ciri_ciri', []);
        if (! is_array($raw)) {
            return [];
        }

        $cleaned = [];
        foreach ($raw as $item) {
            if (is_string($item)) {
                $trimmed = trim($item);
                if ($trimmed !== '') {
                    $cleaned[] = $trimmed;
                }
            }
        }

        return $cleaned;
    }

    private function storeCustomImage(Request $request): string
    {
        $file = $request->file('custom_image');
        $directory = public_path('uploads/bangun-datar');
        File::ensureDirectoryExists($directory);

        $extension = $file->getClientOriginalExtension() ?: 'png';
        $filename = Str::uuid()->toString().'.'.$extension;
        $file->move($directory, $filename);

        return 'uploads/bangun-datar/'.$filename;
    }

    private function deleteCustomImage(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'uploads/bangun-datar/')) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
