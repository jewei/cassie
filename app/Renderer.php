<?php

namespace App;

use App\Actions\Certificates\FontNotFoundException;
use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Support\Facades\Storage;

final readonly class Renderer
{
    public function execute(Template $template, Certificate $certificate): void
    {
        $start = microtime(true);

        /** @var ArrayObject<string, string> $properties */
        $properties = $template->properties;

        $pdf = new \TCPDF(
            orientation: $properties['orientation'] ?? 'L',
            unit: $properties['unit'] ?? 'mm',
            format: $properties['format'],
        );

        $pdf->setTitle($properties['title'] ?? '');
        $pdf->setSubject($properties['subject'] ?? '');
        $pdf->setAuthor($properties['author'] ?? '');
        $pdf->setCreator($properties['creator'] ?? '');
        $pdf->setKeywords($properties['keywords'] ?? '');
        $pdf->setMargins(0, 0, 0, true);
        $pdf->setAutoPageBreak(false, 0);

        $pdf->AddPage(
            orientation: $properties['orientation'] ?? 'L',
            format: $properties['format'],
            keepmargins: true,
            tocpage: false
        );

        $pdf->Image(
            file: Storage::disk('local')->path($template->image),
            fitonpage: true,
        );

        $pdf->Image(
            file: $this->getNameImageData($certificate->name, $properties),
            fitonpage: true,
        );

        Storage::disk($certificate->disk)->put(
            $certificate->filename,
            $pdf->Output(dest: 'S')
        );

        $certificate->update([
            'render_time' => (microtime(true) - $start) * 1000,
            'rendered_at' => now(),
        ]);
    }

    /**
     * @param  ArrayObject<string, string>  $properties
     */
    private function getNameImageData(string $name, ArrayObject $properties): string
    {
        $paperFormat = '\App\Enums\\'.$properties['format'];

        [$paperWidth, $paperHeight] = $properties['orientation'] === 'L'
            ? [$paperFormat::HEIGHT->value, $paperFormat::WIDTH->value]
            : [$paperFormat::WIDTH->value, $paperFormat::HEIGHT->value];

        $font = Storage::disk('local')->exists('fonts'.DIRECTORY_SEPARATOR.$properties['font'])
            ? Storage::disk('local')->path('fonts'.DIRECTORY_SEPARATOR.$properties['font'])
            : throw new FontNotFoundException('Font not found: '.$properties['font']);
        [$lowerLeftX,, $lowerRightX, $lowerRightY,, $upperRightY] = imagettfbbox((float) $properties['font_size'], 0, $font, $name) ?: [0, 0, 0, 0]; // https://bugs.php.net/bug.php?id=81334

        [$red, $green, $blue] = str_split($properties['font_colour'] ?? 'FFFFFF', 2);

        /** @var \GdImage $image */
        $image = imagecreatetruecolor($paperWidth, $paperHeight);
        imagesavealpha($image, true);

        $transparent = imagecolorallocatealpha(image: $image, red: 0, green: 0, blue: 0, alpha: 127);
        imagefill(image: $image, x: 0, y: 0, color: (int) $transparent);

        imagettftext(
            image: $image,
            size: (float) $properties['font_size'],
            angle: 0,
            x: (int) ($paperWidth - $lowerRightX + $lowerLeftX + $properties['x_offset']) / 2,
            y: (int) ($paperHeight - $lowerRightY + $upperRightY + $properties['y_offset']) / 2,
            color: (int) imagecolorallocate(image: $image, red: (int) hexdec($red), green: (int) hexdec($green), blue: (int) hexdec($blue)),
            font_filename: $font,
            text: $name
        );

        ob_start();
        imagepng($image);

        return '@'.ob_get_clean();
    }
}
