<?php
return [
    /**
     * Thuế VAT = false | '10%' ... false hoặc empty để bỏ qua
     */
    'vat'         => '10%',
    /**
     * Tự động add các route
     */
    'add_route'   => true,
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares' => [
        'frontend' => null,
        'backend'  => 'admin',
    ],
    /**
     * Slug các trang đặc biệt
     */
    'pages'       => [
        'contact_us'       => 'contact-us',
        'terms_conditions' => 'terms-conditions',
        'order_success'    => 'order-success',
    ],
];
