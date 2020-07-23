<?php


add_filter('gutenberg_forms_submission__status__akismet', function ($entry) {


    $cwp_gf_akismet_api = new cwp_gf_addon_Akismet($entry);

    $test = $cwp_gf_akismet_api->test();

    $spam = array(
        'status'      => 'spam',
        'can_proceed' => false,
    );

    $success = array(
        'status'      => 'success',
        'can_proceed' => true,
    );

    $error = array(
        'status'      => 'error',
        'can_proceed' => false
    );

    switch ($test) {

        case 'success':
            return $success;
            break;
        case 'error':
            return $error;
            break;
        case 'spam':
            return $spam;
            break;
    }
});
