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
                ) as $itemIndex) {
                if (env('EVALUATION_ITEM_' . $itemIndex . '_NAME')) {
                    $items[] = [
                        'name' => env('EVALUATION_ITEM_' . $itemIndex . '_NAME'),
                        'values' => explode(',', env('EVALUATION_ITEM_' . $itemIndex . '_VALUES')),
                        'required' => env('EVALUATION_ITEM_' . $itemIndex . '_REQUIRED'),
                    ];
                }
            }
            return $items;
        })(),
    ],

];
