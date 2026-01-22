<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the dashboard routes prefix and middleware.
    |
    */
    'routes' => [
        'prefix' => env('TYRO_DASHBOARD_PREFIX', 'dashboard'),
        'middleware' => ['web', 'auth'],
        'name_prefix' => 'tyro-dashboard.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Roles
    |--------------------------------------------------------------------------
    |
    | Users with these roles will have full access to admin features
    | (user management, role management, privilege management, settings).
    |
    */
    'admin_roles' => ['admin', 'super-admin'],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model to use throughout the dashboard.
    |
    */
    'user_model' => env('TYRO_DASHBOARD_USER_MODEL', 'App\\Models\\User'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for lists.
    |
    */
    'pagination' => [
        'users' => 15,
        'roles' => 15,
        'privileges' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | Customize the dashboard appearance.
    |
    */
    'branding' => [
        'app_name' => env('TYRO_DASHBOARD_APP_NAME', env('APP_NAME', 'Laravel')),
        'logo' => env('TYRO_DASHBOARD_LOGO', null),
        'logo_height' => env('TYRO_DASHBOARD_LOGO_HEIGHT', '32px'),
        'favicon' => env('TYRO_DASHBOARD_FAVICON', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific dashboard features.
    |
    */
    'features' => [
        'user_management' => true,
        'role_management' => true,
        'privilege_management' => true,
        'settings_management' => true,
        'profile_management' => true,
        'activity_log' => false, // Future feature
    ],

    /*
    |--------------------------------------------------------------------------
    | Protected Resources
    |--------------------------------------------------------------------------
    |
    | Resources that cannot be deleted through the dashboard.
    |
    */
    'protected' => [
        'roles' => ['admin', 'super-admin', 'user'],
        'users' => [], // Add user IDs that cannot be deleted
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    |
    | Configure which widgets appear on the dashboard home.
    |
    */
    'widgets' => [
        'stats' => true,
        'recent_users' => true,
        'role_distribution' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configure dashboard notifications behavior.
    |
    */
    'notifications' => [
        'show_flash_messages' => true,
        'auto_dismiss_seconds' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Dynamic Resources (CRUD)
    |--------------------------------------------------------------------------
    |
    | Define your resources here to automatically generate CRUD interfaces.
    |
    */
    // 'resources' => [
    //     // Example:
    //     // 'posts' => [
    //     //     'model' => 'App\Models\Post',
    //     //     'title' => 'Posts',
    //     //     'icon' => '<svg>...</svg>', // Optional SVG icon
    //     //     'fields' => [
    //     //         'title' => ['type' => 'text', 'label' => 'Title', 'rules' => 'required'],
    //     //         'content' => ['type' => 'textarea', 'label' => 'Content'],
    //     //     ],
    //     // ],
    // ],
    'resources' => [
        // 'posts' => [
        //     'model' => 'App\Models\Post',
        //     'title' => 'Posts',
        //     'fields' => [
        //         'title' => ['type' => 'text', 'label' => 'Title', 'rules' => 'required'],
        //         'content' => ['type' => 'textarea', 'label' => 'Content'],
        //         'category_id' => [
        //             'type' => 'select',
        //             'label' => 'Category',
        //             'relationship' => 'category', // Name of the relationship method in Post model
        //             'option_label' => 'name',
        //         ],
        //         'is_published' => ['type' => 'boolean', 'label' => 'Published'],
        //     ],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource UI Settings
    |--------------------------------------------------------------------------
    |
    | Configure the appearance and behavior of resource forms and lists.
    |
    */
    'resource_ui' => [
        'show_global_errors' => env('TYRO_SHOW_GLOBAL_ERRORS', true),
        'show_field_errors' => env('TYRO_SHOW_FIELD_ERRORS', true),
    ],
];
