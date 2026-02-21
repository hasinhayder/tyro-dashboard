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
        
        // Sidebar colors (supports any CSS color value: hex, rgb, hsl, etc.)
        'sidebar_bg' =>  env('TYRO_DASHBOARD_SIDEBAR_BG',null), // Custom background color for sidebar
        'sidebar_text' => env('TYRO_DASHBOARD_SIDEBAR_TEXT',null), // Custom text color for sidebar
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Bar
    |--------------------------------------------------------------------------
    |
    | Configuration for the admin notice bar displayed at the top of the dashboard.
    |
    */
    'admin_bar' => [
        'enabled' => env('TYRO_DASHBOARD_ADMIN_BAR_ENABLED', false),
        'message' => env('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', ''),
        'bg_color' => env('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', '#000000'),
        'text_color' => env('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', '#ffffff'),
        'align' => env('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', 'left'),
        'height' => env('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', '40px'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Collapsible Sidebar
    |--------------------------------------------------------------------------
    |
    | Enable or disable the collapsible sidebar feature.
    |
    */
    'collapsible_sidebar' => env('TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR', true),

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
        'invitation_system' => env('TYRO_DASHBOARD_ENABLE_INVITATION', true),
        'audit_logs' => env('TYRO_DASHBOARD_ENABLE_AUDIT_LOGS', true),
        'activity_log' => false, // Future feature
        'profile_photo_upload' => env('TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO', false),
        'gravatar' => env('TYRO_DASHBOARD_ENABLE_GRAVATAR', false),
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
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configure default settings for file uploads in resources.
    |
    */
    'uploads' => [
        'disk' => env('TYRO_DASHBOARD_UPLOAD_DISK', 'public'),
        'directory' => env('TYRO_DASHBOARD_UPLOAD_DIRECTORY', 'uploads'),
        'auto_delete_on_resource_delete' => env('TYRO_DASHBOARD_AUTO_DELETE_UPLOADS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Configuration
    |--------------------------------------------------------------------------
    |
    | Configure settings for user profile photos and gravatar support.
    |
    */
    'profile_photo' => [
        'disk' => env('TYRO_DASHBOARD_PROFILE_PHOTO_DISK', 'public'),
        'directory' => env('TYRO_DASHBOARD_PROFILE_PHOTO_DIRECTORY', 'profile_images'),
        'max_size' => env('TYRO_DASHBOARD_PROFILE_PHOTO_MAX_SIZE', 10240), // in KB (default 10MB)
        'width' => env('TYRO_DASHBOARD_PROFILE_PHOTO_WIDTH', 400),
        'height' => env('TYRO_DASHBOARD_PROFILE_PHOTO_HEIGHT', 400),
        'quality' => env('TYRO_DASHBOARD_PROFILE_PHOTO_QUALITY', 90),
        'crop_position' => env('TYRO_DASHBOARD_PROFILE_PHOTO_CROP', 'center'), // top, center, bottom
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'auto_delete_on_user_delete' => true,
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

    /*
    |--------------------------------------------------------------------------
    | Disable Examples
    |--------------------------------------------------------------------------
    |
    | If this is true, the "Examples" section in the sidebar will be hidden
    | and the example routes will be disabled.
    |
    */
    'disable_examples' => env('TYRO_DASHBOARD_DISABLE_EXAMPLES', false),
];
