<?php

# Registering the integration in the gutenberg forms

add_filter('gutenberg_forms_integrations', function( $integrations ) {

    $akismet_api_exist = cwp_gf_addon_Akismet::exist();
    $guide = plugin_dir_path( __FILE__ ) . 'guide/guide.html';

    $configurations = array(
        'title' => 'Akismet',
        'is_pro'  => false,
        'type'  => 'autoResponder',
        'guide' => '', # file_get_contents( $guide )
        'description' => 'Akismet Addon allows you to detect spams.',
        'banner'    => 'https://cdn.wpsmackdown.com/wp-content/uploads/2018/08/akismet-anti-spam-plugin.png',
        'fields' => array(),
        'query_fields' => array(),
        'api_fields' => array(
            'name' => array(
                'label' => 'Name',
                'restriction' => 'cwp/name',
                'required' => false
            ),
            'email' => array(
                'label' => 'Email',
                'restriction' => 'cwp/email',
                'required' => false
            ),
            'url' => array(
                'label' => 'Url',
                'restriction' => 'cwp/website',
                'required' => false
            ),
            'message' => array(
                'label' => 'Message',
                'restriction' => 'cwp/message',
                'required' => false
            )
        )
    ); 


    if (!$akismet_api_exist) {
        # if the user does not have akismet plugin 
        # disabling the integration by adding some options
        # & showing a notice prompting to install Akismet plugin

        $plugin_repo_url = "https://wordpress.org/plugins/akismet/";

        $configurations['is_disabled'] = true; // disabling the integration
        $configurations['error'] = array(
            'status'    => 'error',
            'message'   => sprintf('Unable to access Akismet API please make sure that <a href="%1$s" target="__blank">Akismet Plugin</a> is installed/configured & activated with the API KEY before activating Akismet Addon', $plugin_repo_url)
        );
        
    }

    $integrations['akismet'] = $configurations;

    return $integrations;

});
