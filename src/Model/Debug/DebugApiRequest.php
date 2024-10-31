<?php

namespace QuizAd\Model\Debug;

use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Placements\Placement;
use QuizAd\Model\Placements\Website;

/**
 * Debug request sent to API.
 */
class DebugApiRequest
{
    protected $loginRequest;
    protected $serverIp;
    protected $scope;
    private   $message;
    private   $credentials;
    private   $websites;
    private   $type;
    private   $placement;

    /**
     * RegistrationInvoice constructor.
     *
     * @param                     $message
     * @param Credentials         $credentials
     * @param Website             $websites
     * @param                     $placement
     * @param                     $serverIp
     * @param                     $scope
     * @param                     $type
     */
    public function __construct(
        $message,
        Credentials $credentials,
        Website $websites,
        $placement,
        $serverIp,
        $scope,
        $type
    )
    {
        $this->serverIp    = $serverIp;
        $this->scope       = $scope;
        $this->message     = $message;
        $this->credentials = $credentials;
        $this->websites    = $websites;
        $this->type        = $type;
        $this->placement   = $placement;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'message'     => $this->message,
            'code'        => 200,
            'date'        => time(),
            'publisherId' => $this->credentials->getPublisherId(),
            'websiteUrl'  => get_site_url(),
            'scope'       => $this->scope,
            'details'     => [
                'requestIp'        => $this->serverIp,
                'accessToken'      => $this->credentials->getToken(),
                'tokenExpire'      => $this->credentials->getExpireIn(),
                'cId'              => $this->credentials->getId(),
                'website'          => get_object_vars($this->websites),
                'defaultPlacement' => ($this->placement instanceof Placement) ? get_object_vars($this->placement) : null,
                'serverId'         => $this->serverIp,
                'debugType'        => $this->type,
                'adminUrl'         => get_admin_url(),
                'isCache'          => (int)$this->findCacheInPlugins(),
                'phpVersion'       => 'Current PHP version: ' . phpversion(),
                'wpVersion'        => get_bloginfo('version'),
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function findCacheInPlugins()
    {
        foreach (get_plugins() as $plugin)
        {
            if (preg_match('/.*?cache.*/', $plugin['Name'])
                || preg_match('/.*?Cache.*/', $plugin['Name']))
            {
                return true;
            }
        }
        return false;
    }
}