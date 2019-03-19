<?php

return [

    'evaluations' => [
        'items' => (function () {
            $items = [];
            /* .env items such as
             *
             *   EVALUATION_ITEM_01_NAME=item_1_1
             *   EVALUATION_ITEM_01_VALUES=A,B,C,D,E,NV
             *   EVALUATION_ITEM_01_REQUIRED=true
             *
             *   EVALUATION_ITEM_02_NAME=item_1_2
             *   EVALUATION_ITEM_02_VALUES=A,B,C,D,E,NV
             *   EVALUATION_ITEM_02_REQUIRED=false
             *
             * become
             *
             *   Array (
             *     [0] => Array (
             *       [name] => item_1_1
             *       [values] => Array (
             *         [0] => A
             *         [1] => B
             *         [2] => C
             *         [3] => D
             *         [4] => E
             *         [5] => NV
             *       )
             *       [required] => true
             *     )
             *     [1] => Array (
             *       [name] => item_1_2
             *       [values] => Array (
             *         [0] => A
             *         [1] => B
             *         [2] => C
             *         [3] => D
             *         [4] => E
             *         [5] => NV
             *       )
             *       [required] => false
             *     )
             *   )
             */
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

    'eligibilities' => [
        'enforced' => env('ELIGIBILITIES_ENFORCED', false),
    ]

];
