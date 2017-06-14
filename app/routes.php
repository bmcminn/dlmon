<?php



$app->get('/', function($req, $res) {

    return $this->view->render($res, 'home.twig');

});
