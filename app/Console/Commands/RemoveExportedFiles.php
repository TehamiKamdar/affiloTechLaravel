<?php
namespace App\Console\Commands;

use App\Models\ExportFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class RemoveExportedFiles extends Command
{
    protected $signature = "app:remove-exported-files";
    protected $description = "Exported files older than one week will be automatically deleted.";

    public function handle()
    {
        $this->info("Starting export process...");
        $this->deleteOldFiles();
        $this->info("Export process complete!");
        return Command::SUCCESS;
    }

    protected function deleteOldFiles()
    {
        $oldFiles = ExportFiles::where("created_at", "<", Carbon::now()->subWeek())->get();

        foreach ($oldFiles as $fileRecord) {
            $filePath = storage_path($fileRecord->path);

            if (File::exists($filePath)) {
                $this->info("Deleting file: {$fileRecord->file_path}");
                File::delete($filePath);
                $fileRecord->delete();
                $this->info("File record deleted from database.");
            } else {
                $this->warn("File {$fileRecord->file_path} does not exist, skipping deletion, removing record.");
                $fileRecord->delete();
            }
        }

        $this->info("Old files and records successfully deleted.");
    }
}
