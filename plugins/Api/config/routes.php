<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::plugin('Api', ['path' => '/api'], function (RouteBuilder $routes) {
    $symbol_expression = '[\w- ^ . , $ ! & ( ) * ; : < = > @ { } ~]+';  //!#$%&'()*+,-./:;<=>?@[\]^_`{|}~
    $routes->connect('/auth/login', ['controller' => 'auth', 'action' => 'login', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/auth/social-login', ['controller' => 'auth', 'action' => 'socialLogin', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/logout', ['controller' => 'auth', 'action' => 'logout', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect(
        '/auth/change-password',
        ['controller' => 'auth', 'action' => 'changePassword'], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/auth/register', ['controller' => 'auth', 'action' => 'register', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/forget-password', ['controller' => 'auth', 'action' => 'forgetPassword', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/reset-password', ['controller' => 'auth', 'action' => 'resetPassword', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/activate-user', ['controller' => 'auth', 'action' => 'activateUser', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/resend-email-activation',
        ['controller' => 'auth', 'action' => 'resendEmailActivation', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/refresh-token', ['controller' => 'auth', 'action' => 'refreshToken'], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/users/check-email', ['controller' => 'auth', 'action' => 'checkEmail', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/check-phone', ['controller' => 'auth', 'action' => 'checkPhone', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/avatar', ['controller' => 'auth', 'action' => 'editAvatar'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/video', ['controller' => 'auth', 'action' => 'uploadVideo'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/get-avatar', ['controller' => 'auth', 'action' => 'getAvatar'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/get-video', ['controller' => 'auth', 'action' => 'getVideo'], ['routeClass' => 'Api.LangRoute']);

    // currency routes
    $routes->connect(
        '/currency',
        ['controller' => 'portfolio', 'action' => 'getAllCurrency', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/currency/pair',
        ['controller' => 'portfolio', 'action' => 'getAllPair', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/currency/price',
        ['controller' => 'portfolio', 'action' => 'getAllPrice', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    //news routs
    $routes->resources('News');
    $routes->connect(
        '/news/:exchangeInfo/trending',
        ['controller' => 'news', 'action' => 'trending', 'allowWithoutToken' => true],
        ['exchangeInfo' => $symbol_expression, 'pass' => ['exchangeInfo'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/news',
        ['controller' => 'News', 'action' => 'index', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/news/company/:symbol',
        ['controller' => 'news', 'action' => 'getCompanyNews', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/news/:slug',
        ['controller' => 'news', 'action' => 'slugNews', 'allowWithoutToken' => true],
        ['slug' => '[\w-]+', 'pass' => ['slug'], 'routeClass' => 'Api.LangRoute']);
    // page route
    $routes->connect(
        '/page/about-us',
        ['controller' => 'page', 'action' => 'about', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/notifications',
        ['controller' => 'page', 'action' => 'getNotifications', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/read-notification',
        ['controller' => 'page', 'action' => 'readNotification', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/page/term-of-service',
        ['controller' => 'page', 'action' => 'termOfService', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/market/prequalification',
        ['controller' => 'page', 'action' => 'prequalification', '[method]' => ['POST', 'OPTIONS'], 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);

    // forex route
    $routes->connect(
        '/forex',
        ['controller' => 'forex', 'action' => 'index', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/forex/symbol/:forex',
        ['controller' => 'forex', 'action' => 'symbol', 'allowWithoutToken' => true],
        ['forex' => '[\w-]+', 'pass' => ['forex'], 'routeClass' => 'Api.LangRoute']);

    // bonds route
    $routes->connect(
        '/bonds',
        ['controller' => 'bonds', 'action' => 'index', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/bonds/corporate',
        ['controller' => 'bonds', 'action' => 'getCorporateBonds', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/bonds/:bond/news',
        ['controller' => 'bonds', 'action' => 'news'],
        ['bond' => '[\w-]+', 'pass' => ['bond'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/bonds/:bond',
        ['controller' => 'bonds', 'action' => 'historicalPrice', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true],
        ['bond' => '[\w-]+', 'pass' => ['bond'], 'routeClass' => 'Api.LangRoute']);

    //messages routes
    $routes->resources('Messages');
    $routes->connect(
        '/messages',
        ['controller' => 'messages', 'action' => 'index', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/messages',
        ['controller' => 'messages', 'action' => 'add', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/messages/:message_id/share',
        ['controller' => 'messages', 'action' => 'share', '[method]' => ['POST', 'OPTIONS']],
        ['message_id' => '\d+', 'pass' => ['message_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/comments/:id',
        ['controller' => 'messages', 'action' => 'view', '[method]' => ['GET', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/comments/:id',
        ['controller' => 'messages', 'action' => 'delete', '[method]' => ['DELETE', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/ratings',
        ['controller' => 'messages', 'action' => 'addRating', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/comments',
        ['controller' => 'messages', 'action' => 'addComment', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);

    //private-message routes
    $routes->connect(
        '/private-messages',
        ['controller' => 'private', 'action' => 'index', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/private-messages',
        ['controller' => 'private', 'action' => 'privateSave', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/private-messages/searchs',
        ['controller' => 'private', 'action' => 'privateSearch', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/private-messages/:message_id',
        ['controller' => 'private', 'action' => 'editPost', '[method]' => ['POST', 'OPTIONS']],
        ['message_id' => '\d+', 'pass' => ['message_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/private-messages/:message_id',
        ['controller' => 'private', 'action' => 'privateDeleteRoom', '[method]' => ['DELETE', 'OPTIONS']],
        ['message_id' => '\d+', 'pass' => ['message_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/messages/attach',
        ['controller' => 'messages', 'action' => 'attach', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/messages/files',
        ['controller' => 'messages', 'action' => 'getAttachFile', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);


    //companies routes
    $routes->connect('/symbols', ['controller' => 'Companies', 'action' => 'index', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/add-to-watchlist',
        ['controller' => 'watchlist', 'action' => 'createWatchlist', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/watchlist/stock',
        ['controller' => 'companies', 'action' => 'getStocksInfo', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/companies/get-mentioned-symbol',
        ['controller' => 'companies', 'action' => 'getMentionSymbols', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/:symbol/analysis',
        ['controller' => 'companies', 'action' => 'symbol', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/search',
        ['controller' => 'companies', 'action' => 'search', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/trending',
        ['controller' => 'companies', 'action' => 'trending', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/symbols/:symbol/options',
        ['controller' => 'companies', 'action' => 'optionApi', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/symbols/:symbol',
        ['controller' => 'companies', 'action' => 'view', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']);

    $routes->connect(
        '/analysis/sector/:symbol',
        ['controller' => 'analysis', 'action' => 'sector', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis/sector/modal/:symbol',
        ['controller' => 'analysis', 'action' => 'sectorModal', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis/industry/:symbol',
        ['controller' => 'analysis', 'action' => 'industry', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis/:symbol',
        ['controller' => 'analysis', 'action' => 'index', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/add-analysis/:symbol',
        ['controller' => 'analysis', 'action' => 'addAnalysis'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/delete-analysis',
        ['controller' => 'analysis', 'action' => 'deleteAnalysis'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/all-analysis',
        ['controller' => 'analysis', 'action' => 'all'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis-partial-edit',
        ['controller' => 'analysis', 'action' => 'analysisEditPartial'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis-approve',
        ['controller' => 'analysis', 'action' => 'analysisApprove'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis-type',
        ['controller' => 'analysis', 'action' => 'analysisType'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis-make-copy',
        ['controller' => 'analysis', 'action' => 'makeCopy'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis-print',
        ['controller' => 'analysis', 'action' => 'analysisPrint'],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis/chart/:symbol',
        ['controller' => 'analysis', 'action' => 'getChartData', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/analysis/time-and-sales/:symbol',
        ['controller' => 'analysis', 'action' => 'timeAndSales', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/symbols/:symbol/statement',
        ['controller' => 'analysis', 'action' => 'companyFinancial', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/symbols/:symbol/financial-statement',
        ['controller' => 'FinancialStatement', 'action' => 'companyFinancial', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/symbols/:symbol/modal/financial-statement',
        ['controller' => 'FinancialStatement', 'action' => 'companyFinancialModal', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/financial-statement/:symbol',
        ['controller' => 'FinancialStatement', 'action' => 'symbol', 'allowWithoutToken' => true],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/country',
        ['controller' => 'companies', 'action' => 'country', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/sectors/performance',
        ['controller' => 'companies', 'action' => 'sectorPerformance', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/sectors',
        ['controller' => 'companies', 'action' => 'sectors', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);

    //stock routs
    $routes->connect(
        '/stocks-list',
        ['controller' => 'stocks', 'action' => 'stockList', '[method]' => ['GET', 'OPTIONS', 'POST'], 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks',
        ['controller' => 'stocks', 'action' => 'ajaxManageCompanySearch', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/filter-list',
        ['controller' => 'stocks', 'action' => 'stocksFilter', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/top',
        ['controller' => 'stocks', 'action' => 'top', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/worst',
        ['controller' => 'stocks', 'action' => 'worst', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/info',
        ['controller' => 'stocks', 'action' => 'info', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/simulation_chart',
        ['controller' => 'stocks', 'action' => 'simulationChart', '[method]' => ['GET', 'OPTIONS'], 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/:symbol',
        ['controller' => 'stocks', 'action' => 'getStockBySymbol', 'allowWithoutToken' => true, '[method]' => ['GET', 'OPTIONS']],
        ['symbol' => $symbol_expression, 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/:stock_id',
        ['controller' => 'stocks', 'action' => 'edit', '[method]' => ['POST', 'OPTIONS']],
        ['stock_id' => '\d+', 'pass' => ['stock_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/stocks/:stock_id',
        ['controller' => 'stocks', 'action' => 'delete', '[method]' => ['DELETE', 'OPTIONS']],
        ['stock_id' => '\d+', 'pass' => ['stock_id'], 'routeClass' => 'Api.LangRoute']);

    //transaction route
    $routes->connect(
        '/orders',
        ['controller' => 'portfolio', 'action' => 'orderDataTable', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);

    $routes->connect(
        '/orders-list',
        ['controller' => 'portfolio', 'action' => 'orderLists', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/transactions',
        ['controller' => 'portfolio', 'action' => 'transactions'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/brokers',
        ['controller' => 'portfolio', 'action' => 'getBrokerList', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/order-preview',
        ['controller' => 'portfolio', 'action' => 'savePreview', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/orders',
        ['controller' => 'portfolio', 'action' => 'placeOrder', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/transactions/:transaction_id/cancel',
        ['controller' => 'portfolio', 'action' => 'transactionCancel'],
        ['transaction_id' => '\d+', 'pass' => ['transaction_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/currency-list',
        ['controller' => 'portfolio', 'action' => 'getCurrencyList'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/broker/:broker_id',
        ['controller' => 'portfolio', 'action' => 'getBrokerFee'],
        ['broker_id' => '\d+', 'pass' => ['broker_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/get_companies',
        ['controller' => 'portfolio', 'action' => 'getCompany', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/get_company_price',
        ['controller' => 'portfolio', 'action' => 'getCompanyPrice', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    //simulation route
    $routes->connect(
        '/simulations',
        ['controller' => 'portfolio', 'action' => 'simulations', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/simulations',
        ['controller' => 'portfolio', 'action' => 'addSimulation', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/simulations/delete',
        ['controller' => 'portfolio', 'action' => 'simulationDelete', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/simulations/chart',
        ['controller' => 'portfolio', 'action' => 'simulationsChart', 'allowWithoutToken' => true, '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/simulations/stock-current-price',
        ['controller' => 'portfolio', 'action' => 'getSimulationsStockCurrentPrice', 'allowWithoutToken' => true, '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);

    //followers route
    $routes->connect(
        '/followers',
        ['controller' => 'followers', 'action' => 'followersList',
        '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);

    $routes->connect(
        '/following',
        ['controller' => 'followers', 'action' => 'followingList', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
   
    $routes->connect(
    '/follow/all',
    ['controller' => 'followers', 'action' => 'allList', '[method]' => ['GET', 'OPTIONS']], 
    ['routeClass' => 'Api.LangRoute']);
    
    $routes->connect(
        '/follow/:user_id',
        ['controller' => 'followers', 'action' => 'follow', '[method]' => ['POST', 'OPTIONS']],
        ['user_id' => '[\w-]+', 'pass' => ['user_id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/unfollow/:user_id',
        ['controller' => 'followers', 'action' => 'unfollow', '[method]' => ['POST', 'OPTIONS']],
        ['user_id' => '[\w-]+', 'pass' => ['user_id'], 'routeClass' => 'Api.LangRoute']);

    // users route
    $routes->connect('/users/me', ['controller' => 'users', 'action' => 'me', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/me', ['controller' => 'users', 'action' => 'edit', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/all', ['controller' => 'users', 'action' => 'userList', '[method]' => ['OPTIONS', 'GET']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/get-user', ['controller' => 'users', 'action' => 'getUser', '[method]' => ['OPTIONS', 'GET']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/users/alerts',
        ['controller' => 'users', 'action' => 'alerts', '[method]' => ['OPTIONS', 'POST', 'GET']],
        ['routeClass' => 'Api.LangRoute']);

    //watchlist controller
    $routes->connect(
        '/watchlist',
        ['controller' => 'watchlist', 'action' => 'getWatchlistAll', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/groups/stocks',
        ['controller' => 'watchlist', 'action' => 'myWatchlistGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/groups/forex',
        ['controller' => 'watchlist-forex', 'action' => 'myForexGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/groups/bonds',
        ['controller' => 'watchlist-bonds', 'action' => 'myBondGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/',
        ['controller' => 'watchlist', 'action' => 'createWatchlist', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/:id',
        ['controller' => 'watchlist', 'action' => 'deleteWatchlist', '[method]' => ['DELETE', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/group/:id',
        ['controller' => 'watchlist', 'action' => 'editGroup', '[method]' => ['POST', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/group/:id',
        ['controller' => 'watchlist', 'action' => 'deleteGroup', '[method]' => ['DELETE', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/group/',
        ['controller' => 'watchlist', 'action' => 'getWatchlistGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/group/',
        ['controller' => 'watchlist', 'action' => 'createWatchlistGroup', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/groups',
        ['controller' => 'watchlist', 'action' => 'getAllGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/symbol/:symbol',
        ['controller' => 'watchlist', 'action' => 'deleteBySymbol', '[method]' => ['DELETE', 'OPTIONS']],
        ['symbol' => '\w+', 'pass' => ['symbol'], 'routeClass' => 'Api.LangRoute']);

    //bond watchlist
    $routes->connect(
        '/watchlist/bond-item',
        ['controller' => 'watchlistBonds', 'action' => 'index', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond/group',
        ['controller' => 'watchlistBonds', 'action' => 'add', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond/group',
        ['controller' => 'watchlistBonds', 'action' => 'getAllBondsGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond/group-edit/:id',
        ['controller' => 'watchlistBonds', 'action' => 'edit', '[method]' => ['POST', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond/group/:id',
        ['controller' => 'watchlistBonds', 'action' => 'delete', '[method]' => ['DELETE', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond-item/:isinCode',
        ['controller' => 'watchlistBonds', 'action' => 'addItem', '[method]' => ['POST', 'OPTIONS']],
        ['isinCode' => '[\w-]+', 'pass' => ['isinCode'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/bond-item/:isinCode',
        ['controller' => 'watchlistBonds', 'action' => 'removeItem', '[method]' => ['DELETE', 'OPTIONS']],
        ['isinCode' => '[\w-]+', 'pass' => ['isinCode'], 'routeClass' => 'Api.LangRoute']);

    //forex watchlist
    $routes->connect(
        '/watchlist/forex/group',
        ['controller' => 'watchlistForex', 'action' => 'add', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex/group',
        ['controller' => 'watchlistForex', 'action' => 'getAllForexGroup', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex/group-edit/:id',
        ['controller' => 'watchlistForex', 'action' => 'edit', '[method]' => ['POST', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex/group/:id',
        ['controller' => 'watchlistForex', 'action' => 'delete', '[method]' => ['DELETE', 'OPTIONS']],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex-item',
        ['controller' => 'watchlistForex', 'action' => 'index', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex-item/:traderId',
        ['controller' => 'watchlistForex', 'action' => 'addItem', '[method]' => ['POST', 'OPTIONS']],
        ['traderId' => '[\w-]+', 'pass' => ['traderId'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/watchlist/forex-item/:traderId',
        ['controller' => 'watchlistForex', 'action' => 'removeItem', '[method]' => ['DELETE', 'OPTIONS']],
        ['traderId' => '[\w-]+', 'pass' => ['traderId'], 'routeClass' => 'Api.LangRoute']);


    //subscription routes
    $routes->connect(
        '/subscription/product/:name',
        ['controller' => 'subscription', 'action' => 'defineProduct', '[method]' => ['POST', 'OPTIONS']],
        [ 'name' => '[\w-]+', 'pass' => ['name'], 'routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/plan',
        ['controller' => 'subscription', 'action' => 'createPlan', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/create',
        ['controller' => 'subscription', 'action' => 'createWithStripe', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/customer',
        ['controller' => 'subscription', 'action' => 'createCustomer', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription',
        ['controller' => 'subscription', 'action' => 'subscription', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/card-info',
        ['controller' => 'subscription', 'action' => 'cardInfo', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/add-card',
        ['controller' => 'subscription', 'action' => 'addCard', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/delete-card',
        ['controller' => 'subscription', 'action' => 'deleteCard', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/change-default-card',
        ['controller' => 'subscription', 'action' => 'changeDefaultCard', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/get-customer-info',
        ['controller' => 'subscription', 'action' => 'customerInfo', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/get-products',
        ['controller' => 'subscription', 'action' => 'getProducts', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/get-plans',
        ['controller' => 'subscription', 'action' => 'getPlans', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/get-subscriptions',
        ['controller' => 'subscription', 'action' => 'getSubscription', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/webhook',
        ['controller' => 'subscription', 'action' => 'webhook', 'allowWithoutToken' => true, '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/charge_succeed_webhook',
        ['controller' => 'subscription', 'action' => 'chargeSucceedWebhook', 'allowWithoutToken' => true, '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/charge_failed_webhook',
        ['controller' => 'subscription', 'action' => 'chargeFailedWebhook', 'allowWithoutToken' => true, '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/webhook-customize',
        ['controller' => 'subscription', 'action' => 'webhookCustomize', '[method]' => ['POST', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/payment-history',
        ['controller' => 'subscription', 'action' => 'paymentHistory', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/subscription/get-invoice/:invoice_id',
        ['controller' => 'subscription', 'action' => 'getInvoice', '[method]' => ['GET', 'OPTIONS']],
        [ 'invoice_id' => '[\w-]+', 'pass' => ['invoice_id'], 'routeClass' => 'Api.LangRoute']);
    //ipo routes
    $routes->connect(
        '/ipo',
        ['controller' => 'ipo', 'action' => 'index', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/ipo/markets',
        ['controller' => 'ipo', 'action' => 'markets', '[method]' => ['GET', 'OPTIONS']],
        ['routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/ipo/markets/:market/company/:company',
        ['controller' => 'ipo', 'action' => 'index', '[method]' => ['GET', 'OPTIONS']],
        ['market' => '[\w-]+', 'company' => '[\w-]+', 'pass' => ['market', 'company'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/ipo/set-interest/:company_id',
        ['controller' => 'ipo', 'action' => 'interest', '[method]' => ['GET', 'OPTIONS']],
        ['company_id' => '\d', 'pass' => ['company_id'], 'routeClass' => 'Api.LangRoute']
    );
    $routes->connect(
        '/ipo/unset-interest/:company_id',
        ['controller' => 'ipo', 'action' => 'notInterest', '[method]' => ['GET', 'OPTIONS']],
        ['company_id' => '\d', 'pass' => ['company_id'], 'routeClass' => 'Api.LangRoute']
    );
    // research route
    $routes->connect(
        '/research/jam-market/how-to-invest',
        ['controller' => 'research', 'action' => 'howTOInvest'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/jam-market/forex-crypto',
        ['controller' => 'research', 'action' => 'forexCrypto'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/jam-market/jamaica-stock-exchange',
        ['controller' => 'research', 'action' => 'jamaicaStockExchange'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/jam-market/strategies',
        ['controller' => 'research', 'action' => 'strategies'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/us-market/investing-on-us-market',
        ['controller' => 'research', 'action' => 'investingUsMarket'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/us-market/forex-crypto-currencies',
        ['controller' => 'research', 'action' => 'forexUsMarket'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/us-market/the-us-stock-exchange',
        ['controller' => 'research', 'action' => 'usStockExchange'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/research/us-market/compare-us-broker',
        ['controller' => 'research', 'action' => 'compareUsBroker'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->connect(
        '/events',
        ['controller' => 'companies', 'action' => 'getEvents'],
        ['routeClass' => 'Api.LangRoute']);
    $routes->scope('/subscriptions', [], function ($route) {
        $route->connect('/create', ['controller' => 'subscription', 'action' => 'createWithBrain', '[method]' => ['POST', 'OPTIONS']]);
//        $route->connect('/execute', ['controller' => 'subscription', 'action' => 'executeAgreement', 'allowWithoutToken' => true]);
    });
    $routes->fallbacks(DashedRoute::class);
}
);
