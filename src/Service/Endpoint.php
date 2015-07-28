<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TQ\ExtDirect\Description\ServiceDescriptionFactory;
use TQ\ExtDirect\Http\ServiceDescriptionResponse;
use TQ\ExtDirect\Http\UploadResponse;
use TQ\ExtDirect\Router\RequestFactory;
use TQ\ExtDirect\Router\Router;

/**
 * Class Endpoint
 *
 * @package TQ\ExtDirect\Service
 */
class Endpoint
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var ServiceDescriptionFactory
     */
    private $descriptionFactory;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var string
     */
    private $descriptor;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param string                    $id
     * @param ServiceDescriptionFactory $descriptionFactory
     * @param Router                    $router
     * @param RequestFactory            $requestFactory
     * @param string                    $descriptor
     * @param bool                      $debug
     */
    public function __construct(
        $id,
        ServiceDescriptionFactory $descriptionFactory,
        Router $router,
        RequestFactory $requestFactory,
        $descriptor,
        $debug = false
    ) {
        $this->id                 = $id;
        $this->descriptionFactory = $descriptionFactory;
        $this->router             = $router;
        $this->requestFactory     = $requestFactory;
        $this->descriptor         = $descriptor;
        $this->debug              = $debug;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $url
     * @param string $format
     * @return ServiceDescriptionResponse
     */
    public function createServiceDescription($url, $format = 'js')
    {
        $serviceDescription = $this->descriptionFactory->createServiceDescription($url);

        if ($format == 'json') {
            $response = JsonResponse::create($serviceDescription);
        } else {
            $response = new ServiceDescriptionResponse($serviceDescription, $this->getDescriptor());
        }

        if ($this->debug) {
            $response->setEncodingOptions(JSON_PRETTY_PRINT);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        $directRequest  = $this->requestFactory->createRequest($request);
        $directResponse = $this->router->handle($directRequest, $request);

        if ($directRequest->isFormUpload()) {
            $response = new UploadResponse($directResponse->getFirst());
        } else {
            $response = JsonResponse::create($directResponse);
        }

        if ($this->debug) {
            $response->setEncodingOptions(JSON_PRETTY_PRINT);
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }
}
