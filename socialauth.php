<?php

require_once 'ASEngine/AS.php';

$provider = @ $_GET['p'];

$token = @ $_GET['token'];

if ( $token == '' || $token == null || $token !== ASSession::get('as_social_token') ) {
    ASSession::destroy('as_social_token');
    die('Wrong social auth token!');
}

if ( $provider == '' || $provider == null )
    die('Wrong provider.');

switch($provider) {
    case 'twitter':
        if ( ! TWITTER_ENABLED ) die ('This provider is not enabled.');
        break;
    case 'facebook':
        if ( ! FACEBOOK_ENABLED ) die ('This provider is not enabled.');
        break;
    case 'google':
        if ( ! GOOGLE_ENABLED ) die ('This provider is not enabled.');
        break;

    default:
        die('This provider is not supported!');
}

require_once 'vendor/hybridauth/Hybrid/Auth.php';

$config = dirname(__FILE__) . '/vendor/hybridauth/config.php';

try {
    $hybridauth = new Hybrid_Auth( $config );

    $adapter = $hybridauth->authenticate( $provider );

    $userProfile = $adapter->getUserProfile();

    // determine if this is first time that user logs in via this social network
    if ( $register->registeredViaSocial($provider, $userProfile->identifier) )
    {
        // user already exist and his account is connected with this provider, log him in
        $user = $register->getBySocial($provider, $userProfile->identifier);
        $login->byId($user['user_id']);
        header('Location: index.php');
    }
    else
    {
        // user is not registred via this social network, check if his email exist in db
        // and associate his account with this provider

        $validator = new ASValidator();

        if ( $validator->emailExist($userProfile->email) )
        {
            // hey, this user is registered here, just associate social account with his email
            $user = $register->getByEmail($userProfile->email);
            $register->addSocialAccount($user['user_id'], $provider, $userProfile->identifier);
            $login->byId($user['user_id']);
            header('Location: index.php');
        }
        else
        {
            // this is first time that user is registring on this webiste, create his account
            $user = new ASUser(null);

            // generate unique username
            // for example, if two users with same display name (that is usually first and last name)
            // are registred, they will have the same username, so we have to add some random number here
            $username = str_replace(' ', '', $userProfile->displayName);

            $tmpUsername = $username;

            $i = 0;
            $max = 50;

            while ( $validator->usernameExist($tmpUsername) ) {

                //try maximum 50 times
                // Note: Chances for going over 2-3 times are really really low but just in case,
                // if somehow it always generate username that is already in use, prevent database from crashing
                // and generate some random unique username (it can be changed by administrator later)
                if ( $i > $max )
                    break;

                $tmpUsername = $username . rand(1, 10000);
                $i++;
            }

            // there are more than 50 trials, generate random username
            if ( $i > $max )
                $tmpUsername = uniqid('user', true);


            $username = $tmpUsername;

            $info = array(
                'email'         => $userProfile->email == null ? '' : $userProfile->email,
                'username'      => $username,
                'password'      => $register->hashPassword(hash('sha512', $register->randomPassword())),
                'confirmed'     => 'Y',
                'register_date' => date('Y-m-d H:i:s')
            );
            $details = array(
                'first_name' => $userProfile->firstName == null ? '' : $userProfile->firstName,
                'last_name'  => $userProfile->lastName == null ? '' : $userProfile->lastName,
                'address'    => $userProfile->address == null ? '' : $userProfile->address,
                'phone'      => $userProfile->phone == null ? '' : $userProfile->phone
            );

            $db->insert('as_users', $info);

            $userId = $db->lastInsertId();

            $details['user_id'] = $userId;

            $db->insert('as_user_details', $details);

            $register->addSocialAccount($userId, $provider, $userProfile->identifier);
            $login->byId($userId);
            header('Location: index.php');
        }

    }
}
catch( Exception $e ) {
    // something happened (social auth cannot be completed), just redirect user to login page
    // Note: to debug check hybridauth documentation for error codes
    header('Location: login.php');
}


