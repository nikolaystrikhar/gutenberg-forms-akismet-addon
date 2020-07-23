<?php

class cwp_gf_addon_Akismet
{


    public function __construct($entry)
    {
        $this->entry = $entry;
        $this->is_enabled = get_option('cwp__enable__akismet') === '1' ? true : false;
    }

    /**
     * Will check if the api of akismet exist and can be utilized
     * @return bool [existence of the Akismet Api]
     * 
     */

    public static function exist(): bool
    {

        $akismet_post_existence = function_exists('akismet_http_post');
        return $akismet_post_existence;
    }

    /**
     * @param string {id} The id of the field which value is required
     * @return value [can be of any type depending on the value] 
     */

    public function field(string $id)
    {
        if (isset($this->entry[$id])) {
            return $this->entry[$id];
        }

        return null; # null exception
    }

    /**
     * Test the given entry and mark as ( VALID OR INVALID )
     * @return string Possible return values are ['spam', 'error', 'success'] 
     */

    public function test(): string
    {

        if (!$this->is_enabled)
            return 'error'; # skipping the test if the integration is disabled from the backend

        if (!self::exist())
            return 'spam'; # unable to find akismet api therefore stopping further execution

        global $akismet_api_host, $akismet_api_port; # some akismet globals

        $name = $this->field('name');
        $email = $this->field('email');
        $url = $this->field('url');
        $message = $this->field('message');

        # data to be delivered to Akismet
        $data = array(
            'comment_author'        => $name,
            'comment_author_email'  => $email,
            'comment_author_url'    => $url,
            'comment_content'       => $message,
            'user_ip'               => $_SERVER['REMOTE_ADDR'],
            'user_agent'            => $_SERVER['HTTP_USER_AGENT'],
            'referrer'              => '',
            'blog'                  => get_site_url(),
            'blog_lang'             => 'en_US',
            'blog_charset'          => 'UTF-8',
        );


        # constructing query string based on data

        $query_string = http_build_query($data);

        # finally providing the data to akismet along with some global variables

        $response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);

        # getting the result

        $result = (is_array($response) && isset($response[1])) ? $response[1] : 'false';

        if ($result === 'false') {
            return 'spam';
        }

        return 'success';
    }
}
