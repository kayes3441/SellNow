<?php

return function ($router) {

    // Home
    $router->get('/', function () {
        echo "Welcome to SellNow! <a href='/login'>Login</a>";
    });

    // Authentication Routes
    $router->get('/login', 'AuthController@loginForm');
    $router->post('/login', 'AuthController@login');

    $router->get('/register', 'AuthController@registerForm');
    $router->post('/register', 'AuthController@register');

    $router->any('/logout', function () {
        session_destroy();
        header("Location: /");
        exit;
    });

    $router->get('/dashboard', 'AuthController@dashboard');

    // Product Routes
    $router->get('/products/add', 'ProductController@create');
    $router->post('/products/add', 'ProductController@store');

    // Cart Routes
    $router->get('/cart', 'CartController@index');
    $router->post('/cart/add', 'CartController@add');
    $router->any('/cart/clear', 'CartController@clear');

    // Checkout Routes
    $router->get('/checkout', 'CheckoutController@index');
    $router->post('/checkout/process', 'CheckoutController@process');
    $router->any('/payment', 'CheckoutController@payment');
    $router->get('/checkout/success', 'CheckoutController@success');

    $router->get('/{username}', 'PublicController@profile');

    // 404 Handler
    $router->notFound(function () {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1><a href='/'>Go Home</a>";
    });
};
