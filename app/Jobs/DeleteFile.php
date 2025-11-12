<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\File;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[WithoutRelations]
class DeleteFile implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public File $file, public bool $flag=false){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $file = $this->file;
        $path = $file->path;
        $disk = $file->private ? 'private' : 'public';
        if (Storage::disk($disk)->exists($path))
        {
            Storage::disk($disk)->delete($path);
            if (!$this->flag){
                $file->delete();
            }
            Log::info("Deleted file {$path} from {$disk} disk.");
        }
    }
}
