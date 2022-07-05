<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.01.16
 * Time: 10:33
 */

namespace TQ\ExtDirect\Router;

/**
 * Class AbstractResponseDecorator
 *
 * @package TQ\ExtDirect\Router
 */
abstract class AbstractResponseDecorator extends Response
{
    /**
     * @var Response
     */
    private $decorated;

    /**
     * @param Response $decorated
     */
    public function __construct(Response $decorated)
    {
        parent::__construct($decorated->getType());
        $this->decorated = $decorated;
    }

    public function jsonSerialize(): mixed
    {
        $serialized = $this->decorated->jsonSerialize();

        return array_merge(
            $serialized,
            $this->serializeAdditionalData()
        );
    }

    /**
     * @return Response
     */
    protected function getDecorated()
    {
        return $this->decorated;
    }

    /**
     * @return array
     */
    abstract protected function serializeAdditionalData();
}
