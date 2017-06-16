<?php



$app->get('/', function($req, $res, $props) {

    $userAgent = isset($_SERVER['HTTP_USER_AGENT'])         ? $_SERVER['HTTP_USER_AGENT']       : '';
    $userLangs = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])    ? $_SERVER['HTTP_ACCEPT_LANGUAGE']  : '';

    $userHash = md5(getRealIpAddr() . $userAgent . $userLangs);


    $model = [
        'site' => [
            'title' => 'Download Monitor'
        ]
    ,   'user' => [
            'udid' => $userHash
        ]
    ,   '_GET' => $_SERVER
    ];


    echo "<pre>";
    print_r($model);
    echo "</pre>";

    return $this->view->render($res, 'home.twig', $model);

})->setName('home');







// $app->get('/', function ($req, $res, $args) {
//     return $this->view->render($res, 'home.twig', [
//         'name' => 'bobo'
//     ]);
// })->setName('home');



// // Define named route
// $app->get('/hello/{name}', function ($req, $res, $args) {
//     return $this->view->render($res, 'home.twig', [
//         'name' => $args['name']
//     ]);
// })->setName('profile');
