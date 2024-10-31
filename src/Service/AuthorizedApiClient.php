<?php


namespace QuizAd\Service;


use QuizAd\Model\AuthorizedResponse;

class AuthorizedApiClient
{
    protected $apiHost;

    /**
     * AuthorizedApiClient constructor.
     *
     * @param $apiHost
     */
    public function __construct($apiHost)
    {
        $this->apiHost = $apiHost;
    }

    /**
     * @param       $url
     * @param       $token
     * @param array $options
     *
     * @return AuthorizedResponse
     */
    public function get($url, $token, $options = [])
    {
        $response = wp_remote_request($url, [
            'method'  => 'GET',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
        ]);
        $status   = wp_remote_retrieve_response_code($response);

        return new AuthorizedResponse($status, $response);
    }

    /**
     * @param       $url
     * @param       $token
     * @param array $body
     * @param array $options
     *
     * @return AuthorizedResponse
     */
    public function post($url, $token, array $body = [], $options = [])
    {
        $response = wp_remote_request($url, [
            'method'  => 'POST',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'body'    => json_encode($body)
        ]);
        $status   = wp_remote_retrieve_response_code($response);

        return new AuthorizedResponse($status, $response);
    }

    /**
     * @param       $url
     * @param       $token
     * @param array $options
     *
     * @return AuthorizedResponse
     */
    public function delete($url, $token, $options = [])
    {
        $response = wp_remote_request($url, [
            'method'  => 'DELETE',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
        ]);
        $status   = wp_remote_retrieve_response_code($response);

        return new AuthorizedResponse($status, $response);
    }
}