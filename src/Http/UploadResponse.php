<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Http;


use Symfony\Component\HttpFoundation\JsonResponse;
use TQ\ExtDirect\Router\AbstractResponse;

/**
 * Class UploadResponse
 *
 * @package TQ\ExtDirect\Http
 */
class UploadResponse extends JsonResponse
{
    /**
     * @var AbstractResponse|null
     */
    private $response;

    /**
     * @param AbstractResponse|null $response
     * @param int                   $status
     * @param array                 $headers
     */
    public function __construct(
        AbstractResponse $response = null,
        $status = 200,
        $headers = array()
    ) {
        parent::__construct(null, $status, $headers);

        if ($response !== null) {
            $this->setResponse($response);
        }
    }

    /**
     * @return AbstractResponse|null
     */
    public function geResponse()
    {
        return $this->response;
    }

    /**
     * @param AbstractResponse $response
     * @return $this
     */
    public function setResponse(AbstractResponse $response)
    {
        $this->response = $response;
        $this->setData($this->response);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function update()
    {
        if ($this->response !== null) {
            $this->headers->set('Content-Type', 'text/html; charset=UTF-8');
            $output = preg_replace("/&quot;/", '\\&quot;', htmlspecialchars($this->data, ENT_NOQUOTES, 'UTF-8', true));

            return $this->setContent('<html><body><textarea>' . $output . '</textarea></body></html>');
        } else {

            return parent::update();
        }
    }
}
