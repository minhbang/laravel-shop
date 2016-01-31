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
        'backend'  => 'role:admin',
    ],
    /**
     * Slug các trang đặc biệt
     */
    'pages'       => [
        'contact_us'       => 'contact-us',
        'terms_conditions' => 'terms-conditions',
        'order_success'    => 'order-success',
    ],
    /**
     * Default options
     */
    'options'     => [
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
];
