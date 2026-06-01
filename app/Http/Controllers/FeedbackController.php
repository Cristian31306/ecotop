<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the feedback for administration.
     */
    public function index(Request $request)
    {
        // Solo administradores pueden ver esta sección
        if ($request->user()->role !== 'admin') {
            abort(403, 'No autorizado.');
        }

        $feedbacks = Feedback::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['admin', 'tester']);
        })
        ->with('user')
        ->latest()
        ->get();

        $totalCount = $feedbacks->count();
        $averageRating = $totalCount > 0 ? round($feedbacks->avg('rating'), 1) : 0;

        // Distribución de calificaciones (1 a 5 estrellas)
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        foreach ($feedbacks as $feedback) {
            if (isset($distribution[$feedback->rating])) {
                $distribution[$feedback->rating]++;
            }
        }

        // Convertir a porcentajes para la UI
        $percentages = [];
        foreach ($distribution as $stars => $count) {
            $percentages[$stars] = [
                'count' => $count,
                'percentage' => $totalCount > 0 ? round(($count / $totalCount) * 100) : 0
            ];
        }

        return Inertia::render('Admin/Feedback/Index', [
            'feedbacks' => $feedbacks,
            'averageRating' => $averageRating,
            'totalCount' => $totalCount,
            'distribution' => $percentages,
        ]);
    }

    /**
     * Store a newly created or updated feedback in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback = $request->user()->feedbacks()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()->back()->with('success', '¡Gracias por calificar la aplicación!');
    }
}
