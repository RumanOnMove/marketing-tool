<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function collectionResponse($collections): array
    {
        return [
            'data'  => $collections,
            'links' => [
                'first' => $collections->url(1),
                'last'  => $collections->url($collections->lastPage()),
                'prev'  => $collections->previousPageUrl(),
                'next'  => $collections->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $collections->currentPage(),
                'from'         => $collections->firstItem(),
                'last_page'    => $collections->lastPage(),
                'path'         => $collections->path(),
                'per_page'     => $collections->perPage(),
                'to'           => $collections->lastItem(),
                'total'        => $collections->total(),
            ]
        ];
    }

    // In a helper file or a service class (e.g., BladeGenerator.php)
    function generateBladeFromContent($content)
    {
        $blade = '';

        switch ($content['type']) {
            case 'image':
                $src = isset($content['values']['src']['url']) ? 'src="' . $content['values']['src']['url'] . '"' : '';
                $altText = isset($content['values']['altText']) ? 'alt="' . $content['values']['altText'] . '"' : '';
                $maxWidth = isset($content['values']['src']['maxWidth']) ? 'max-width: ' . $content['values']['src']['maxWidth'] . ';' : '';
                $containerPadding = isset($content['values']['containerPadding']) ? 'margin: ' . $content['values']['containerPadding'] . ';' : '';
                $blade .= '<img ' . $src . ' ' . $altText . ' style="' . $maxWidth . $containerPadding . '">';
                break;
            case 'heading':
                $headingType = $content['values']['headingType'] ?? 'h1';
                $fontSize = isset($content['values']['fontSize']) ? 'font-size: ' . $content['values']['fontSize'] . ';' : '';
                $color = isset($content['values']['color']) ? 'color: ' . $content['values']['color'] . ';' : '';
                $textAlign = isset($content['values']['textAlign']) ? 'text-align: ' . $content['values']['textAlign'] . ';' : '';
                $lineHeight = isset($content['values']['lineHeight']) ? 'line-height: ' . $content['values']['lineHeight'] . ';' : '';
                $fontWeight = isset($content['values']['fontWeight']) ? 'font-weight: ' . $content['values']['fontWeight'] . ';' : '';
                $blade .= '<' . $headingType . ' style="' . $fontSize . $color . $textAlign . $lineHeight . $fontWeight . '">' . $content['values']['text'] . '</' . $headingType . '>';
                break;
            case 'text':
                $fontSize = isset($content['values']['fontSize']) ? 'font-size: ' . $content['values']['fontSize'] . ';' : '';
                $textAlign = isset($content['values']['textAlign']) ? 'text-align: ' . $content['values']['textAlign'] . ';' : '';
                $lineHeight = isset($content['values']['lineHeight']) ? 'line-height: ' . $content['values']['lineHeight'] . ';' : '';
                $blade .= '<p style="' . $fontSize . $textAlign . $lineHeight . '">' . $content['values']['text'] . '</p>';
                break;
            // Add other cases for different content types as needed.
            default:
                // Handle unknown content types or skip if not needed.
                break;
        }

        return $blade;
    }

    function generateBladeFromTemplateData($data)
    {
        $blade = '';

        if (isset($data['body']['rows'])) {
            foreach ($data['body']['rows'] as $row) {
                $blade .= '<div class="row">';
                if (isset($row['columns'][0]['contents']) && is_array($row['columns'][0]['contents'])) {
                    foreach ($row['columns'][0]['contents'] as $content) {
                        $blade .= $this->generateBladeFromContent($content);
                    }
                }
                $blade .= '</div>';
            }
        }

        return $blade;
    }

    function generateBladeTemplateFromJSON($json)
    {
        $templateData = $json;
        $htmlContent  = $this->generateBladeFromTemplateData($templateData);

        $bladeTemplate = <<<EOT
            <!DOCTYPE html>
            <html>
            <head>
                <title>Your Email Subject</title>
            </head>
            <body style="background-color: {$templateData['values']['backgroundColor']}; color: {$templateData['values']['textColor']}; font-family: {$templateData['values']['fontFamily']['value']}">
                <div style="width: {$templateData['values']['contentWidth']}px; margin: 0 auto;">
                    {$htmlContent}
                </div>
            </body>
            </html>
            EOT;

        return $bladeTemplate;
    }

}
