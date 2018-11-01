<?php
/*
    qmvc - A small but powerful MVC framework written in PHP.
    Copyright (C) 2016 ThrDev
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Database Configuration
define("DB_USERNAME", getenv("MYSQL_USERNAME"));
define("DB_PASSWORD", getenv("MYSQL_PASSWORD"));
define("DB_HOST", getenv("MYSQL_HOST"));
define("DB_NAME", getenv("MYSQL_DB_NAME"));

// Required for Google's Recaptcha, Uncomment if needed
/*
define("RECAPTCHA_SITE_KEY", "");
define("RECAPTCHA_PRIVATE_KEY", "");
define("RECAPTCHA_URL", "https://www.google.com/recaptcha");
*/

// Set to the root URL of your website.
define("SITE_URL", "http://ctf.local:3400/");

// Change to something random.
define("SESSION_NAME", "hckme");

// Use memcached? Must have php-memcached and memcached installed.
define("USE_MEMCACHED", false);
define("MEMCACHED_HOST", "127.0.0.1");
define("MEMCACHED_PORT", "11211");

define("FLAG_1", getenv("FLAG_1"));
define("FLAG_2", getenv("FLAG_2"));
define("FLAG_3", getenv("FLAG_3"));
define("FLAG_4", getenv("FLAG_4"));

/* 
=== Routes === 
Can now either be passed in one at a time or once in an array.

*/
$routes = [
    '/' => [
        'controller' => 'index',
    ],
    '/404' => [
        'controller' => 'notfound',
        'error_page' => '404',
    ],
    '/test' => [
        'controller' => 'test',
    ],
];

$router->Connect($routes);

// OR

/*
$router->Connect("/", array("controller" => "index"));
$router->Connect("/template", array('controller' => 'templating'));
$router->Connect("/404", array("controller" => "404", "error_page" => "404"));
$router->Connect('/submit', array('controller' => 'index', 'action' => 'submit'));
*/
