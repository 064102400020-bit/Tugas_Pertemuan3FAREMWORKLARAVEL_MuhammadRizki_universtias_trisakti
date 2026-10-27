<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * GET /api/authors
     * Menampilkan semua penulis
     */
    public function index(): JsonResponse
    {
        $authors = Author::withCount('books')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data authors berhasil diambil',
            'data'    => $authors,
        ], 200);
    }

    /**
     * POST /api/authors
     * Menambahkan penulis baru
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:authors,email|max:255',
            'bio'         => 'nullable|string',
            'nationality' => 'nullable|string|max:100',
        ]);

        $author = Author::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil ditambahkan',
            'data'    => $author,
        ], 201);
    }

    /**
     * GET /api/authors/{id}
     * Menampilkan detail satu penulis beserta buku-bukunya
     */
    public function show(string $id): JsonResponse
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data author berhasil diambil',
            'data'    => $author,
        ], 200);
    }

    /**
     * PUT/PATCH /api/authors/{id}
     * Memperbarui data penulis
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'email'       => 'nullable|email|unique:authors,email,' . $author->id . '|max:255',
            'bio'         => 'nullable|string',
            'nationality' => 'nullable|string|max:100',
        ]);

        $author->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil diperbarui',
            'data'    => $author->fresh(),
        ], 200);
    }

    /**
     * DELETE /api/authors/{id}
     * Menghapus penulis
     */
    public function destroy(string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan',
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil dihapus',
        ], 200);
    }
}