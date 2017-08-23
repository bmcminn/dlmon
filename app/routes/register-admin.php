<?php



// ======================================================================
//  ADMIN REGISTRATION
// ======================================================================

$router->get(ROUTES['register_admin'], function() use ($db, $model, $twig) {

    // MAKE SURE PEEPS CAN'T USE THIS UNLESS IT'S THE FIRST TIME THEY SET IT UP
    $stmt = $db->query("SELECT * FROM users WHERE type='admin'");
    $users = $stmt->fetch(PDO::FETCH_ASSOC);

    // IF THE # OF ADMIN USERS IS >0, OR THE USER IS NOT CURRENTLY LOGGED IN, BUG OUT
    if (!isset($_SESSION['user']) && !empty($users)) {
        redirect(ROUTES['login']);
        echo 'ADMIN EXISTS';
        return;
    }


    if (empty($users)) {
        $model['new_setup_message'] = '<span class="h1">Welcome to [CLIENT-PORTFOLIO]!</span><br><br>Since this appears to be a brand new instance, you must first setup your admin user profile to get started.';
    }


    $model['form'] = [];

    $model['form']['title']         = 'Register Admin';
    $model['form']['submitLabel']   = 'Register';
    $model['form']['id']            = 'register-admin';
    $model['form']['actionRoute']   = ROUTES['register_admin'];

    $model['form']['fields'] = [
        [
            'label'         => 'Full Name'
        ,   'type'          => 'text'
        ,   'required'      => true
        ,   'name'          => 'fullname'
        ]
    ,   [
            'label'         => 'Email Address'
        ,   'type'          => 'text'
        ,   'placeholder'   => 'example@email.com'
        ,   'required'      => true
        ,   'name'          => 'email'
    ,   ]
    ,   [
            'label'         => 'Password'
        ,   'type'          => 'password'
        ,   'required'      => true
        ,   'name'          => 'password'
        ]
    ,   [
            'label'         => 'Password Confirm'
        ,   'type'          => 'password'
        ,   'required'      => true
        ,   'name'          => 'password_confirm'
        ]
    ,   [
            'label'         => 'Password Confirm'
        ,   'type'          => 'hidden'
        ,   'name'          => 'type'
        ,   'value'         => 'admin'
        ]
    ];

    echo $twig->render('register-user.twig', $model);

    return;

});





// ===========================================================================

// POST: register admin queries for setting up new admin users
$router->post(ROUTES['register_admin'], function() use ($db) {

    // Sanitize form submission data
    $fullname           = trim(filter_var($_POST['fullname'],           FILTER_SANITIZE_STRING));
    $email              = trim(filter_var($_POST['email'],              FILTER_SANITIZE_EMAIL));
    $password           = trim(filter_var($_POST['password'],           FILTER_SANITIZE_STRING));
    $password_confirm   = trim(filter_var($_POST['password_confirm'],   FILTER_SANITIZE_STRING));
    $type               = trim(filter_var($_POST['type'],               FILTER_SANITIZE_STRING));

    $res = [];

    // CHECK IF FIELDS ARE FILLED OUT COMPLETELY
    if (empty($fullname)) {
        $res['fullname'] = 'Field cannot be empty';
    }

    if (empty($email)) {
        $res['email'] = 'Field cannot be empty';
    }

    if (empty($password)) {
        $res['password'] = 'Field cannot be empty';
    }

    if (empty($password_confirm)) {
        $res['password_confirm'] = 'Field cannot be empty';
    }

    if (empty($type)) {
        $res['type'] = 'Field cannot be empty';
    }


    // CHECK IF PASSWORDS MATCH
    if ($password !== $password_confirm) {
        $res['password']           = 'Passwords do not match';
        $res['password_confirm']   = 'Passwords do not match';
    }


    // CHECK IF EMAIL IS VALID
    if (!preg_match('/.*@.*\..*/i', $email)) {
        $res['email'] = 'Email address is not correct format: <code>example@email.com</code>';
    }


    // setup response content type as JSON
    header('content-type: json');

    // IF THERE ARE ERRORS, SEND THE INFO BACK TO THE USER
    if (!empty($res)) {
        echo json_encode($res);
        return;
    }


    // VALIDATE IF THE USER ALREADY EXISTS
    $stmt = $db->query("SELECT * FROM users WHERE type='admin' AND email='{$email}'");
    $users = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($users)) {
        $res['notice'] = [
            'message'   => 'Admin user already exists'
        ,   'level'     => 'danger'
        ];

        echo json_encode($res);
        return;
    }


    // HASH OUR USER PASSWORD
    $password = hashPassword($password);


    // PREPARE OUR SQL FOR INSERTING NEW USER
    $stmt = $db->prepare("INSERT INTO `users`(email, fullname, password, type) VALUES(:email, :fullname, :password, :type)");

    // Assign user values, but trim trailing whitespace on each of them
    $stmt->bindValue(':email',     $email);
    $stmt->bindValue(':fullname',  $fullname);
    $stmt->bindValue(':password',  $password);
    $stmt->bindValue(':type',      $type);

    $stmt->execute();


    // RETURN THE ROUTE THE FORM SHOULD REDIRECT TO
    $res['redirect'] = ROUTES['login'];
    echo json_encode($res);

    return;

});
