<?php

namespace App\Http\Web\Controllers;

class SwaggerController
{
    public function listSwaggers()
    {
        $urls = [];
        foreach (config('serve-swagger.urls') as $url) {
            $urls[] = [
                'url' => url($url['url']),
                'name' => $url['name'],
            ];
        }

        return response()->json(['urls' => $urls], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }
}
