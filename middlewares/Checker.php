<?php

class Checker
{
    public function __construct()
    {
        add_action('init', function () {
            return $this->registration_check();
        }, 1);
    }

    public function registration_check()
    {
        if (!str_contains($_SERVER['REQUEST_URI'], 'laractrl') && str_contains($_SERVER['REQUEST_URI'], 'wp-admin') && get_option('laractrl_options', false)) {
            $body = json_decode($this->checkRemotly());

            if (isset($body->status) and !$body->status) {
                update_option('laractrl_status', 'false');
                return $this->locked($body);
                exit;
            } else if (isset($body->status) and $body->status == true) {
                update_option('laractrl_status', 'true');
            } else if (!$this->checkLocaly()) {
                return $this->locked($body);
                exit;
            }
        } else if (!str_contains($_SERVER['REQUEST_URI'], 'laractrl')) {
            if (!$this->checkLocaly()) {
                $body = json_decode($this->checkRemotly());

                if (isset($body->status) and !$body->status) {
                    update_option('laractrl_status', 'false');
                    return $this->locked($body);
                    exit;
                }
            }
        }
    }

    public function checkLocaly()
    {
        if (get_option('laractrl_status', 'true') == 'true') {
            return true;
        } else {
            return false;
        }
    }

    public function checkRemotly()
    {
        $response = get_transient('lc_verifie');

        if (false === $response) {
            $response = wp_remote_get('http://laractrl.com/api/v1/verifie', [
                'headers' => [
                    'app' => get_option('laractrl_options', false) ? get_option('laractrl_options')['laractrl_field_app_key'] : '',
                    'ip' => $_SERVER['SERVER_ADDR'],
                    'domain' => str_replace(['https://', 'http://'], '', get_option('siteurl', $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? $_SERVER['REQUEST_URI']))
                ]
            ]);

            set_transient('lc_verifie', $response, 5);
        }

        return $response['body'];
    }

    public function locked($body)
    {
        $HTML = file_get_contents(__DIR__ . '/../assets/locked.html');

        $HTML = str_replace('{{LC_MESSAGE}}', $body->message ?? 'App Locked', $HTML);
        $HTML = str_replace('{{LC_CODE}}', $body->code ?? '---', $HTML);

        echo $HTML;
        exit;
    }
}
