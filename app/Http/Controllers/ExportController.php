<?php

namespace App\Http\Controllers;

use App\Models\Template;
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

    // public function certificates(): StreamedResponse
    // {
    // }
}
