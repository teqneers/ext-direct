<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Exception;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class BadRequestException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
class BadRequestException extends \InvalidArgumentException
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @param HttpRequest     $request
     * @param \Exception|null $previous
     * @return BadRequestException
     */
    public static function createMethodNotAllowed(HttpRequest $request, \Exception $previous = null)
    {
        return new self(
            $request,
            'Only POST requests are allowed',
            0,
            $previous
        );
    }

    /**
     * @param HttpRequest     $request
     * @param \Exception|null $previous
     * @return BadRequestException
     */
    public static function createMalformedJsonString(HttpRequest $request, \Exception $previous = null)
    {
        return new self(
            $request,
            'The JSON string in invalid',
            0,
            $previous
        );
    }

    /**
     * @param HttpRequest     $request
     * @param \Exception|null $previous
     * @return BadRequestException
     */
    public static function createInvalidExtDirectRequest(HttpRequest $request, \Exception $previous = null)
    {
        return new self(
            $request,
            'The Ext direct request is invalid',
            0,
            $previous
        );
    }

    /**
     * @param HttpRequest     $request
     * @param \Exception|null $previous
     * @return BadRequestException
     */
    public static function createMissingInformation(HttpRequest $request, \Exception $previous = null)
    {
        return new self(
            $request,
            'The Ext direct request is missing vital information',
            0,
            $previous
        );
    }


    /**
     * @param HttpRequest $request
     * @param string      $message
     * @param int         $code
     * @param \Exception  $previous
     */
    public function __construct(HttpRequest $request, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->request = $request;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}
