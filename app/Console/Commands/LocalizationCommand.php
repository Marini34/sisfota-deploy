<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LocalizationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:localization';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = base_path('resources/views');
        $pattern = "/__\(['\"].*?['\"]\)/";

        $results = $this->findFilesWithLocalization($directory, $pattern);

        if (!empty($results)) {
            $this->info("Files containing __(''):");
            foreach ($results as $file) {
                $this->line("- " . $file);
            }
        } else {
            $this->warn("No files found containing __('').");
        }
    }

    private function findFilesWithLocalization($dir, $pattern)
    {
        $results = [];
        $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($items as $item) {
            if ($item->isFile()) {
                $filePath = $item->getPathname();
                $content = file_get_contents($filePath);

                if (preg_match($pattern, $content)) {
                    $results[] = $filePath;
                }
            }
        }

        return $results;
    }
}
