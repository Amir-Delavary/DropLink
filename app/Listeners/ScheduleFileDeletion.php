<?php

namespace App\Listeners;

use App\Events\FileUploaded;
use App\Jobs\DeleteFile;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class ScheduleFileDeletion
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FileUploaded $event): void
    {
        DeleteFile::dispatch($event->file)->delay(Carbon::parse($event->file->expires_at));
    }
}
