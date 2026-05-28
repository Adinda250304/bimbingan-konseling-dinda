<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::with('author')->latest()->paginate(10);
        return view('admin.artikel', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'nullable',
        ]);

        $thumbnailPath = 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=60';
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/artikel'), $filename);
            $thumbnailPath = '/uploads/artikel/' . $filename;
        }

        Artikel::create([
            'author_id' => Auth::id(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'thumbnail' => $thumbnailPath,
            'is_published' => $request->has('is_published') || $request->input('is_published') == '1',
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'nullable',
        ]);

        $artikel = Artikel::findOrFail($id);
        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'is_published' => $request->has('is_published') || $request->input('is_published') == '1',
        ];

        if ($request->hasFile('thumbnail')) {
            // Delete old file if it is local
            if ($artikel->thumbnail && strpos($artikel->thumbnail, '/uploads/artikel/') === 0) {
                $oldPath = public_path($artikel->thumbnail);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/artikel'), $filename);
            $data['thumbnail'] = '/uploads/artikel/' . $filename;
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Delete local thumbnail if exists
        if ($artikel->thumbnail && strpos($artikel->thumbnail, '/uploads/artikel/') === 0) {
            $oldPath = public_path($artikel->thumbnail);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
