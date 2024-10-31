<?php

namespace QuizAd\Service\Registration;

class IpProvider
{
    public function getServerIp()
    {
        if (isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['SERVER_ADDR'];
        }
        return gethostbyname($_SERVER['SERVER_NAME']);
    }

    public function getHost()
    {
        return $_SERVER['SERVER_NAME'];
    }

}