<?php

namespace App\Observers;

use App\Models\PaperSubmission;

class PaperSubmissionObserver
{
    /**
     * Handle the PaperSubmission "created" event.
     */
    public function created(PaperSubmission $paperSubmission): void
    {
        //
    }

    /**
     * Handle the PaperSubmission "updated" event.
     */
    public function updated(PaperSubmission $paperSubmission): void
    {
        if ($paperSubmission->wasChanged('status')) {
            $notifyStatuses = ['Accepted', 'Revision Required', 'Revision'];
            if (in_array($paperSubmission->status, $notifyStatuses)) {
                \Illuminate\Support\Facades\Mail::to($paperSubmission->author->email)
                    ->send(new \App\Mail\SubmissionStatusChangedMail($paperSubmission));
            }
        }
    }

    /**
     * Handle the PaperSubmission "deleted" event.
     */
    public function deleted(PaperSubmission $paperSubmission): void
    {
        //
    }

    /**
     * Handle the PaperSubmission "restored" event.
     */
    public function restored(PaperSubmission $paperSubmission): void
    {
        //
    }

    /**
     * Handle the PaperSubmission "force deleted" event.
     */
    public function forceDeleted(PaperSubmission $paperSubmission): void
    {
        //
    }
}
