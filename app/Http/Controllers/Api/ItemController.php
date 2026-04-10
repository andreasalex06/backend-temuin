<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Item::query()
                ->with('user:id,nama,nim')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Item $item): JsonResponse
    {
        $item->load('user:id,nama,nim');

        return response()->json([
            'data' => $item,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));

        $query = Item::query()->with('user:id,nama,nim')->latest();

        if ($keyword !== '') {
            $query->where(function ($builder) use ($keyword): void {
                $builder->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('lokasi', 'like', "%{$keyword}%");
            });
        }

        return response()->json([
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateItem($request);
        $validated['image_path'] = $request->hasFile('image')
            ? $request->file('image')->store('items', 'public')
            : null;

        $item = $request->user()->items()->create($validated);
        $item->load('user:id,nama,nim');

        return response()->json([
            'message' => 'Posting barang berhasil dibuat.',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $this->authorize('update', $item);

        $validated = $this->validateItem($request);
        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }
        $item->update($validated);
        $item->load('user:id,nama,nim');

        return response()->json([
            'message' => 'Posting barang berhasil diperbarui.',
            'data' => $item,
        ]);
    }

    public function destroy(Item $item): JsonResponse
    {
        $this->authorize('delete', $item);
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();

        return response()->json([
            'message' => 'Posting barang berhasil dihapus.',
        ]);
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:50'],
            'status' => ['required', Rule::in(Item::STATUSES)],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
