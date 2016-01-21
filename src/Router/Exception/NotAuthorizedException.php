<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 21.01.16
 * Time: 17:04
 */

namespace TQ\ExtDirect\Router\Exception;

/**
 * Class NotAuthorizedException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
class NotAuthorizedException extends \RuntimeException
{
    /**
     */
    public function __construct()
    {
        parent::__construct('Not authorized to access requested method');
    }
}
