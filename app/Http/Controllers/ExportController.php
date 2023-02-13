<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function participants(Template $template): StreamedResponse
    {
        return response()->streamDownload(function () use ($template) {
            $file = fopen('php://output', 'w');

            if ($file === false) {
                throw new \ErrorException('Failed to open stream.');
            }

            foreach ($template->certificates as $certificate) {
                fputcsv($file, [$certificate->name, $certificate->email, route('public.show-certificate', $certificate)]);
            }

            fclose($file);
        }, $template->name.'.csv');
    }

    public function certificates(Template $template, \ZipArchive $zip): BinaryFileResponse
    {
        Storage::disk('local')->makeDirectory('zips');

        $filename = storage_path('app/zips/'.preg_replace('/[^A-Za-z0-9_.]/u', '', $template->name).'.zip');
        $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($template->certificates as $certificate) {
            if ($certificate->file_exists) {
                $zip->addFile($certificate->path, $certificate->download_filename);
            }
        }

        $zip->close();

        return response()->download($filename);
    }
}
