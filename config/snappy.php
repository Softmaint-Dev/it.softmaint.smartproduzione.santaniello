<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"'),
        'timeout' => false,
        'options' => [
            'custom-header' => [
                'Accept-Encoding' => 'gzip',
            ],
        ],

        'env'     => [],
    ],
    
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'options' => [
        // 'enable-local-file-access' => true,
        // 'debug-javascript' => true,
      //  'enable-local-file-access' => true,
        'keep-relative-links' => true,
        'debug' => true,
        
        
        'no-images' => true,
        'no-css' => true,
        'no-javascript' => true,
    ],
];
