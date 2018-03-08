<?php
/*
 * This file is part of the OroApi package.
 *
 * (c) Kenny Wildfeuer <kenny@kennys.io>
 */
namespace KennysIO\OroApiClient;

use Httpful\Mime;
use Httpful\Request;
use Httpful\Response;
use Purl\Url;

/**
 * Api-Class is a REST-client that deals with the ORO-Platform API.
 *
 * @package KennysIo\OroApi
 */
class Api
{
    /**
     * The base URI of the API
     *
     * @var string e.g. http://demo.orocrm.com/api/rest/latest
     */
    protected $baseUrl;

    /**
     * The ORO-UserName
     *
     * @var string
     */
    protected $userName;

    /**
     * The users API-Token
     *
     * @var string
     */
    protected $userApiKey;

    /**
     * Api constructor.
     *
     * @param $baseUrl      e.g. http://demo.orocrm.com/api/rest/latest
     * @param $userName     admin
     * @param $userApiKey   The API-Token e.g. e9350d1dcf9bf7d6ed04cc7c7aac88939e1f71bf
     */
    public function __construct($baseUrl, $userName, $userApiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->userName = $userName;
        $this->userApiKey = $userApiKey;
    }

    /**
     * Builds the URI for the Request.
     *
     * @param string $path
     * @param array  $query
     *
     * @return string
     */
    protected function buildUri($path, $query = []) {

        $url = Url::parse( $this->baseUrl );
        $url->path->add($path . '.json');

        if( $query ) {

            $url->query->setData($query);
        }

        return $url->getUrl();
    }

    /**
     * Gets a request-template with included API authorization.
     *
     * @return Request
     */
    protected function getRequestTemplate() {

        $userName = $this->userName;
        $userApiKey = $this->userApiKey;
        $nonce = base64_encode(substr(md5(uniqid()), 0, 16));
        $created  = date('c');
        $digest   = base64_encode(sha1(base64_decode($nonce) . $created . $userApiKey, true));

        $wsse = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $userName,
            $digest,
            $nonce,
            $created
        );

        $template = Request::init()
            ->expects('application/json')
            ->addHeader("Authorization", "WSSE profile=\"UsernameToken\"")
            ->addHeader("X-WSSE", $wsse);

        return $template;
    }

    /**
     * GET-Request
     *
     * @param       $path
     * @param array $query
     *
     * @return Response
     */
    public function get($path, $query = []) {

        $uri = $this->buildUri($path, $query);
        $requestTemplate = $this->getRequestTemplate();

        Request::ini( $requestTemplate );

        $response = Request::get($uri)->send();

        return $response;
    }

    /**
     * POST-Request
     *
     * @param       $path
     * @param array $data
     * @param array $query
     *
     * @return Response
     */
    public function post($path, $data = [], $query = []) {

        $uri = $this->buildUri($path, $query);
        $requestTemplate = $this->getRequestTemplate();

        Request::ini( $requestTemplate );

        $response = Request::post($uri)
            ->body($data, Mime::FORM)
            ->send();

        return $response;
    }

    /**
     * PUT-Request
     *
     * @param       $path
     * @param array $data
     * @param array $query
     *
     * @return Response
     */
    public function put($path, $data = [], $query = []) {

        $uri = $this->buildUri($path, $query);
        $requestTemplate = $this->getRequestTemplate();

        Request::ini( $requestTemplate );

        $response = Request::put($uri)
            ->body($data, Mime::FORM)
            ->send();

        return $response;
    }

    /**
     * PATCH-Request
     *
     * @param       $path
     * @param array $data
     * @param array $query
     *
     * @return Response
     */
    public function patch($path, $data = [], $query = []) {

        $uri = $this->buildUri($path, $query);
        $requestTemplate = $this->getRequestTemplate();

        Request::ini( $requestTemplate );

        $response = Request::patch($uri)
            ->body($data, Mime::FORM)
            ->send();

        return $response;
    }
}