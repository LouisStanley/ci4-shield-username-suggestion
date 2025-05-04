
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->post('register/suggest-usernames', '\LouisStanley\Ci4ShieldUsernameSuggest\Controllers\RegisterController::suggestUsernames');
