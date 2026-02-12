<?php

return [
    'sale-status' => [
        1 => 'Open',
        2 => 'Closed',
        3 => 'Returned'
    ],
    'setting-data-types' => [
        'int',
        'double',
        'string',
    ],
    'default-pagination' => 15,
    'invoice-export' => [
        "max-items" => 32,

        "item-row-range" => [
            'start' => 12,
            'end' => 43,
        ],

        "item-columns" => [
            "description" => "A",
            "qty" => "F",
            "rate" => "G",
            "total" => "H",
        ],

        "cells" => [
            "invoice-no" => "C7",
            "date" => "G7",

            "car-no" => "C8",
            "model" => "G8",
            
            "customer" => "C9",
            "job-done-by" => "G9",

            "subtotal" => "H44",
            "tax" => "H45",
            "discount" => "H46",
            "grand-total" => "H47"
        ]

    ]

];