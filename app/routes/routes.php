<?php
require_once __DIR__ . '/../views/render.php';

function defineGetRoutes(): array
{
    return
        [
            '/' => [App\Controllers\Client\HomeController::class, 'index'],
            '/view/product' => [App\Controllers\Client\ViewproductController::class, 'index'],
            '/users/register' => fn() => renderView('register'),
            '/users/login' => fn() => renderView('login'),
            'is_client' => [
                '/users/profile' => fn() => renderView('profile'),
            ],
            'is_admin' => [
                '/dashboad' => [App\Controllers\Admin\DashboardController::class, 'index'],

                '/dashboard/products/get-subcategories' => [App\Controllers\Admin\ProductController::class, 'getSubcategories'],
                '/dashboard/products' => [App\Controllers\Admin\ProductController::class, 'index'],
                '/dashboard/products/control' => [App\Controllers\Admin\ProductController::class, 'control'],

                '/dashboard/categories' => [App\Controllers\Admin\CategoryController::class, 'index'],
                '/dashboard/categories/control' => [App\Controllers\Admin\CategoryController::class, 'control'],
                '/dashboard/categories/countProducts' => [App\Controllers\Admin\CategoryController::class, 'countProductsByCategory'],

                '/dashboard/subcategories/control' => [App\Controllers\Admin\SubcategoryController::class, 'control'],
            ],
        ];
}

function definePostRoutes(): array
{
    return [
        '/users/register' => fn() => require_once __DIR__ . '/../controllers/account/register_controller.php',
        '/users/login' => fn() => require_once __DIR__ . '/../controllers/account/login_controller.php',
        'is_admin' => [
            '/dashboard/products/store' => [App\Controllers\Admin\ProductController::class, 'store'],
            '/dashboard/products/edit' => [App\Controllers\Admin\ProductController::class, 'edit'],
            '/dashboard/products/delete' => [App\Controllers\Admin\ProductController::class, 'delete'],

            '/products/save-image' => [App\Controllers\Admin\ImageController::class, 'saveImageProduct'],

            '/dashboard/categories/store' => [App\Controllers\Admin\CategoryController::class, 'store'],
            '/dashboard/categories/edit' => [App\Controllers\Admin\CategoryController::class, 'edit'],
            '/dashboard/categories/delete' => [App\Controllers\Admin\CategoryController::class, 'delete'],

            '/dashboard/subcategories/store' => [App\Controllers\Admin\SubcategoryController::class, 'store'],
            '/dashboard/subcategories/edit' => [App\Controllers\Admin\SubcategoryController::class, 'edit'],
            '/dashboard/subcategories/delete' => [App\Controllers\Admin\SubcategoryController::class, 'delete'],
        ],
        'is_client' => [
            '/users/logout' => fn() => require_once __DIR__ . '/../controllers/account/logout_controller.php',
        ]
    ];
}
