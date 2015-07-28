<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

/**
 * Class Response
 *
 * @package TQ\ExtDirect
 */
class Response extends AbstractTransactionResponse
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @param Request $request
     * @param mixed   $result
     * @return self
     */
    public static function fromRequest(Request $request, $result = null)
    {
        return new self(
            $request->getTid(),
            $request->getAction(),
            $request->getMethod(),
            $result
        );
    }

    /**
     * Constructor
     *
     * @param integer $tid
     * @param string  $action
     * @param string  $method
     * @param mixed   $result
     */
    public function __construct($tid, $action, $method, $result = null)
    {
        parent::__construct(self::TYPE_RPC, $tid, $action, $method);
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                'result' => $this->getResult()
            )
        );
    }
}
