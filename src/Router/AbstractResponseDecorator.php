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
abstract class AbstractResponseDecorator extends AbstractResponse
{
    /**
     * @var AbstractResponse
     */
    private $decorated;

    /**
     * @param AbstractResponse $decorated
     */
    public function __construct(AbstractResponse $decorated)
    {
        parent::__construct($decorated->getType());
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $serialized = $this->decorated->jsonSerialize();

        return array_merge(
            $serialized,
            $this->serializeAdditionalData()
        );
    }

    /**
     * @return AbstractResponse
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
