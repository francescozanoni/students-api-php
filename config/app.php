<?php

return [

    'url' => env('APP_URL', 'http://localhost'),

    'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR,

    'evaluations' => [
        'items' => (function () {
            $items = [];
            foreach (
                array_map(
                    function ($index) {
                        return sprintf('%02d', $index);
                    },
                    range(1, 99)
                ) as $index => $item) {
                if (env('EVALUATION_ITEM_' . $index . '_NAME')) {
                    $items[] = [
                        'name' => env('EVALUATION_ITEM_' . $index . '_NAME'),
                        'values' => explode(',', env('EVALUATION_ITEM_' . $index . '_VALUES')),
                        'required' => env('EVALUATION_ITEM_' . $index . '_REQUIRED'),
                    ];
                }
            }
            return $items;
        })(),
    ],

];
