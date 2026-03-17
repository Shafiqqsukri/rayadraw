<?php

namespace App\Http\Controllers;

use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WishController extends Controller
{
    /**
     * Papar semua ucapan (Wall of Wishes)
     * GET /wishes
     */
    public function index()
    {
        $wishes = Wish::latest()->get();

        return view('wishes.index', compact('wishes'));
    }

    /**
     * Papar form submit ucapan
     * GET /wishes/create
     */
    public function create()
    {
        return view('wishes.create');
    }

    /**
     * Simpan ucapan baru ke database
     * POST /wishes
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle upload gambar
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('wishes', 'public');
        }

        // Simpan ke database
        Wish::create([
            'name'       => $validated['name'],
            'message'    => $validated['message'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('wishes.index')
            ->with('success', 'Ucapan kamu dah disimpan! 🎉');
    }

    /**
     * Admin roll semua wishes — assign duit raya secara random
     * POST /wishes/roll
     */
    public function roll()
    {
        // Ambil semua wishes yang belum roll
        $wishes = Wish::notRolled()->get();

        if ($wishes->isEmpty()) {
            return response()->json([
                'message' => 'Semua dah kena roll!',
                'wishes'  => [],
            ]);
        }

        // Shuffle wishes — Fisher-Yates via Laravel collection
        $shuffled = $wishes->shuffle();

        // Shuffle pool duit raya
        $pool = collect(Wish::AMOUNT_POOL)->shuffle()->values();

        // Assign amount kepada setiap wish
        $results = [];
        foreach ($shuffled as $index => $wish) {
            // Ambil amount dari pool — kalau habis, loop balik dari mula
            $amount = $pool[$index % count(Wish::AMOUNT_POOL)];

            $wish->update([
                'amount'    => $amount,
                'is_rolled' => true,
            ]);

            $results[] = [
                'id'      => $wish->id,
                'name'    => $wish->name,
                'amount'  => $amount,
                'photo'   => $wish->photo_url,
            ];
        }

        return response()->json([
            'message' => 'Roll berjaya!',
            'wishes'  => $results,
        ]);
    }

    /**
     * Reset semua rolls (untuk main semula)
     * POST /wishes/reset
     */
    public function reset()
    {
        Wish::query()->update([
            'amount'    => null,
            'is_rolled' => false,
        ]);

        return redirect()->route('wishes.index')
            ->with('success', 'Reset berjaya! Semua sedia untuk roll semula. 🔄');
    }
}