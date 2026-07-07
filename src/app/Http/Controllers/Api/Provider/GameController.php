<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function index(): JsonResponse
    {
        $games = Game::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Game $game): array => $this->formatGame($game));

        return response()->json([
            'data' => $games,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $game = Game::query()
            ->active()
            ->where('slug', $slug)
            ->first();

        if (! $game) {
            return response()->json([
                'message' => 'Game tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $this->formatGame($game),
        ]);
    }

    public function products(string $slug): JsonResponse
    {
        $game = Game::query()
            ->active()
            ->where('slug', $slug)
            ->first();

        if (! $game) {
            return response()->json([
                'message' => 'Game tidak ditemukan.',
            ], 404);
        }

        $products = $game->products()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('selling_price')
            ->get()
            ->map(fn ($product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (int) $product->selling_price,
                'is_active' => (bool) $product->is_active,
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }

    public function inputFields(string $slug): JsonResponse
    {
        $game = Game::query()
            ->active()
            ->where('slug', $slug)
            ->first();

        if (! $game) {
            return response()->json([
                'message' => 'Game tidak ditemukan.',
            ], 404);
        }

        $fields = $game->inputFields()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($field): array => [
                'id' => $field->id,
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'is_required' => (bool) $field->is_required,
                'sort_order' => (int) $field->sort_order,
            ]);

        return response()->json([
            'data' => $fields,
        ]);
    }

    private function formatGame(Game $game): array
    {
        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'description' => $game->description ?? null,
            'image_url' => $game->image_path ? asset('storage/' . $game->image_path) : null,
            'is_active' => (bool) $game->is_active,
        ];
    }
}
