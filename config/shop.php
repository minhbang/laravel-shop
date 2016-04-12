<?php
return [
    /**
     * Thuế VAT = false | '10%' ... false hoặc empty để bỏ qua
     */
    'vat'            => false,
    /**
     * Đơn vị tiền tệ
     */
    'currency'       => 'USD',
    'currency_short' => '$',
    'decimals'       => 2,

    /**
     * Tự động add các route
     */
    'add_route'      => true,
    /**
     * Khai báo middlewares cho các Controller, KHÔNG CÓ ghi []
     */
    'middlewares'    => [
        'frontend' => [],
        'backend'  => 'role:admin',
    ],
    /**
     * Slug các trang đặc biệt
     */
    'pages'          => [
        'contact_us'       => 'contact-us',
        'terms_conditions' => 'terms-conditions',
        'payment_failed'    => 'payment-failed',
        'payment_success'    => 'payment-success',
        'payment_canceled'    => 'payment-canceled',
    ],
    /**
     * Default options
     */
    'options'        => [
        'search'   => [
            'sort'      => 'name.asc',
            'page_size' => 6,
            'type'      => 'th',
        ],
        'category' => [
            'sort'      => 'position.asc',
            'page_size' => 6,
            'type'      => 'th',
        ],
        'product'  => [
            'sort'      => 'updated.asc',
            'page_size' => 6,
            'type'      => 'th',
        ],
    ],
    /**
     * Paypal Payment settings
     */
    'paypal'         => [
        // Paypal credential
        'client_id' => env('PP_CLIENT_ID'),
        'secret'    => env('PP_SECRET'),

        // Paypal SDK configuration
        'config'    => [
            /**
             * Available option 'sandbox' or 'live'
             */
            'mode'                   => 'sandbox',

            /**
             * Specify the max request time in seconds
             */
            'http.ConnectionTimeOut' => 30,

            /**
             * Whether want to log to a file
             */
            'log.LogEnabled'         => true,

            /**
             * Specify the file that want to write on
             */
            'log.FileName'           => storage_path() . '/logs/paypal.log',

            /**
             * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
             */
            'log.LogLevel'           => 'FINE',
        ],
    ],
];
