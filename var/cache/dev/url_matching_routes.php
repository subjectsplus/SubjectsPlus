<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/subjects/guide.php' => [[['_route' => 'guidebyParam', '_controller' => 'App\\Controller\\Patron\\GuideController::showPublicGuideByQueryParams'], null, null, null, false, false, null]],
        '/api/autocomplete/guides.json' => [[['_route' => 'guidesAutocomplete', '_controller' => 'App\\Controller\\Patron\\GuideController::autocompleteGuides'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'index', '_controller' => 'App\\Controller\\Patron\\IndexController::index'], null, null, null, false, false, null]],
        '/index.php' => [[['_route' => 'index.php', '_controller' => 'App\\Controller\\Patron\\IndexController::index'], null, null, null, false, false, null]],
        '/subjects' => [[['_route' => 'subjects', '_controller' => 'App\\Controller\\Patron\\IndexController::index'], null, null, null, true, false, null]],
        '/subjects/index.php' => [[['_route' => 'subjects/index.php', '_controller' => 'App\\Controller\\Patron\\IndexController::index'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\AuthController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\AuthController::logout'], null, null, null, false, false, null]],
        '/api/autocomplete/databases.json' => [[['_route' => 'databasesAutocomplete', '_controller' => 'App\\Controller\\Patron\\DatabaseListController::autocompleteDatabases'], null, null, null, false, false, null]],
        '/subjects/databases.php' => [[['_route' => 'public_database_list', '_controller' => 'App\\Controller\\Patron\\DatabaseListController::index'], null, null, null, false, false, null]],
        '/control/guides/link_checker.php' => [[['_route' => 'staff_link_checker', '_controller' => 'App\\Controller\\Staff\\LinkCheckerController::index'], null, null, null, false, false, null]],
        '/control/guides/check_links.json' => [[['_route' => 'link_checker_api', '_controller' => 'App\\Controller\\Staff\\LinkCheckerController::links'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/subjects/((?!.*?\\.php).*)(*:33)'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:71)'
                    .'|wdt/([^/]++)(*:90)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:135)'
                            .'|router(*:149)'
                            .'|exception(?'
                                .'|(*:169)'
                                .'|\\.css(*:182)'
                            .')'
                        .')'
                        .'|(*:192)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        33 => [[['_route' => 'guidebyShortname', '_controller' => 'App\\Controller\\Patron\\GuideController::showPublicGuide'], ['shortform'], null, null, false, true, null]],
        71 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        90 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        135 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        169 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        182 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        192 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
