<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ExtractLocalization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localization:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract all localization strings from resources/views and save them to lang/id.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Direktori target untuk pencarian file
        $directory = base_path('resources/views');

        // Path file JSON tujuan
        $langFilePath = base_path('lang/id.json');

        // Pola regex untuk mencocokkan __('')
        $pattern = "/__\(\s*['\"](.*?)['\"]\s*\)/";

        // Ekstrak string localization
        $localizationStrings = $this->extractLocalizationStrings($directory, $pattern);

        // Simpan string ke file JSON
        $this->saveToJson($localizationStrings, $langFilePath);

        // Tampilkan pesan sukses
        $this->info("String localization telah diekstrak dan disimpan ke dalam file {$langFilePath}.");
    }

    /**
     * Extract localization strings from files in the given directory.
     *
     * @param string $dir
     * @param string $pattern
     * @return array
     */
    private function extractLocalizationStrings($dir, $pattern)
    {
        $results = [];

        // Iterasi semua file dan direktori di dalam direktori target
        $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($items as $item) {
            // Pastikan item adalah file
            if ($item->isFile()) {
                $filePath = $item->getPathname();

                // Baca isi file
                $content = file_get_contents($filePath);

                // Cari semua string yang cocok dengan pola __('')
                if (preg_match_all($pattern, $content, $matches)) {
                    $results = array_merge($results, $matches[1]);
                }
            }
        }

        // Hapus duplikasi
        return array_unique($results);
    }

    /**
     * Save extracted strings to a JSON file.
     *
     * @param array $strings
     * @param string $filePath
     */
    private function saveToJson($strings, $filePath)
    {
        // Baca file JSON yang sudah ada jika ada
        if (file_exists($filePath)) {
            $existingData = json_decode(file_get_contents($filePath), true);
        } else {
            $existingData = [];
        }

        // Tambahkan string baru ke data JSON
        foreach ($strings as $string) {
            if (!array_key_exists($string, $existingData)) {
                $existingData[$string] = $string; // Default value sama dengan key
            }
        }

        // Simpan data ke file JSON
        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}