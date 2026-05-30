<?php

namespace App\Http\Controllers;

use App\Models\Ecosystem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EcosystemController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Ecosystems/Index', [
            'ecosystems' => Ecosystem::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Ecosystems/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1|unique:ecosystems',
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'is_active' => 'boolean',
            'available_from' => 'nullable|date',
        ]);

        $contentData = [];
        if ($request->has('content') && is_array($request->content)) {
            foreach ($request->content as $card) {
                $imagePath = null;
                if (isset($card['image']) && $card['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $card['image']->store('ecosystems', 'public');
                    $imagePath = '/storage/' . $path;
                }
                $contentData[] = [
                    'text' => $card['text'] ?? '',
                    'image' => $imagePath
                ];
            }
        }
        $validated['content'] = $contentData;

        Ecosystem::create($validated);
        return redirect()->route('admin.ecosystems.index')->with('success', 'Ecosistema creado.');
    }

    public function edit(Ecosystem $ecosystem)
    {
        return Inertia::render('Admin/Ecosystems/Edit', [
            'ecosystem' => $ecosystem
        ]);
    }

    public function update(Request $request, Ecosystem $ecosystem)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1|unique:ecosystems,day_number,'.$ecosystem->id,
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'is_active' => 'boolean',
            'available_from' => 'nullable|date',
        ]);

        $contentData = [];
        if ($request->has('content') && is_array($request->content)) {
            foreach ($request->content as $card) {
                $imagePath = $card['current_image'] ?? null;
                
                if (isset($card['image']) && $card['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $card['image']->store('ecosystems', 'public');
                    $imagePath = '/storage/' . $path;
                }
                
                $contentData[] = [
                    'text' => $card['text'] ?? '',
                    'image' => $imagePath
                ];
            }
        }
        $validated['content'] = $contentData;

        $ecosystem->update($validated);
        return redirect()->route('admin.ecosystems.index')->with('success', 'Ecosistema actualizado.');
    }

    public function destroy(Ecosystem $ecosystem)
    {
        $ecosystem->delete();
        return redirect()->route('admin.ecosystems.index')->with('success', 'Ecosistema eliminado.');
    }
}
