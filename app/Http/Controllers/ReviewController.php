<?php

namespace App\Http\Controllers;

use App\Enums\ReviewStatus;
use App\Http\Requests\StoreReviewRequest;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, FeasibilityApplication $application)
    {
        $user = $request->user();
        $data = $request->validated();

        $review = Review::updateOrCreate(
            [
                'application_id' => $application->id,
                'section' => $data['section'],
            ],
            [
                'reviewer_id' => $user->id,
                'status' => ReviewStatus::from($data['status']),
                'comments' => $data['comments'],
                'recommendations' => $data['recommendations'] ?? null,
                'reviewed_at' => now(),
            ]
        );

        AuditLog::log('section_reviewed', $review, null, [
            'section' => $data['section'],
            'status' => $data['status'],
        ]);

        return back()->with('success', ucfirst($data['section']).' review recorded successfully.');
    }
}
