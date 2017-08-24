<?php

// =========================================================================================================
//  USER SESSION PROCESSES
// =========================================================================================================


// USER LOGIN PAGE
// ------------------------------------------------------------

$router->get(ROUTES['login'], function() use ($model, $twig) {

    if (!isset($model['page'])) {
        $model['page'] = [];
    }

    $model['page']['title'] = 'User Login';

    $model['form'] = [];

    $model['form']['title']            = 'User Login';
    $model['form']['submitLabel']      = 'Login';
    $model['form']['id']               = 'user-login';
    $model['form']['forgotPassword']   = true;
    $model['form']['actionRoute']      = ROUTES['login'];

    $model['form']['fields'] = [
        [
            'label'     => 'User Email'
        ,   'type'      => 'email'
        ,   'required'  => true
        ,   'name'      => 'email'
        ]
    ,   [
            'label'     => 'Password'
        ,   'type'      => 'password'
        ,   'required'  => true
        ,   'name'      => 'password'
        ]
    ];

    echo $twig->render('user-login.twig', $model);

});


// USER LOGIN SUBMISSION
// ------------------------------------------------------------

$router->post(ROUTES['login'], function() use ($db) {

    // Set response header
    header('content-type: application/json');

    // Sanitize form submission data
    $email     = trim(filter_var($_POST['email'],      FILTER_SANITIZE_EMAIL));
    $password  = trim(filter_var($_POST['password'],   FILTER_SANITIZE_STRING));

    // Setup response collection
    $res = [];

    // Validate the fields aren't empty
    if (empty($email)) {
        $res['email'] = 'Field cannot be empty';
    }

    if (empty($password)) {
        $res['password'] = 'Field cannot be empty';
    }

    // Valide email formatting
    if (!preg_match('/.*@.*\..*/i', $email)) {
        $res['email'] = 'Email address is not correct format: <code>example@email.com</code>';
    }

    // if we have errors, return said errors
    if (!empty($res)) {
        echo json_encode($res);
        return;
    }

    // No erros means we look up the user
    $stmt = $db->query("SELECT * FROM `users` WHERE email='${email}'");

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // if the user is not found
    if (empty($user)) {
        $res['notice'] = [
            'message'   => 'User login credentials are not valid.'
        ,   'level'     => 'danger'
        ];
        echo json_encode($res);
        return;
    }

    // if the passwords don't match, bug out
    if (!password_verify($password, $user['password'])) {
        $res['notice'] = [
            'message'   => 'User login credentials are not valid.'
        ,   'level'     => 'danger'
        ];
        echo json_encode($res);
        return;
    }

    // cleanup user model
    unset($user['password']);

    // add usermodel to session
    $_SESSION['user'] = $user;

    // reroute user to [user_type]_dashboard route
    $res['redirect'] = ROUTES['user_dashboard'];

    echo json_encode($res);
    return;

});



// USER LOGOUT/SESSION REMOVAL
// ------------------------------------------------------------

$router->get(ROUTES['logout'], function() use ($twig) {
    session_destroy();
    redirect(ROUTES['login']);
    return;
});

