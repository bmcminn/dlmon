<?php

// =========================================================================================================
//  USER/ADMIN DASHBOARD VIEWS
// =========================================================================================================


// ROUTE: user dashboard
$router->get(ROUTES['user_dashboard'], function() use ($db, $twig, $model) {

    $user = $_SESSION['user'];

    $twig->render('admin-dashboard.twig', $model);

});
