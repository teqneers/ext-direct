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
use TQ\ExtDirect\Description\ServiceDescription;

/**
 * Class ServiceDescriptionResponse
 *
 * @package TQ\ExtDirect\Http
 */
class ServiceDescriptionResponse extends JsonResponse
{
    /**
     * @var string
     */
    private $descriptor;

    /**
     * @param string                  $descriptor
     * @param ServiceDescription|null $serviceDescription
     * @param int                     $status
     * @param array                   $headers
     */
    public function __construct(
        $descriptor = 'Ext.app.REMOTING_API',
        ServiceDescription $serviceDescription = null,
        $status = 200,
        $headers = array()
    ) {
        parent::__construct($serviceDescription, $status, $headers);

        $this->setDescriptor($descriptor);
    }

    /**
     * @param string $descriptor
     * @return $this
     */
    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
        return $this->update();
    }

    /**
     * {@inheritdoc}
     */
    protected function update()
    {
        $this->headers->set('Content-Type', 'application/javascript');

        $namespace      = explode('.', $this->descriptor);
        $baseNamespace  = reset($namespace);
        $jsExpression   = 'var ' . $baseNamespace . ' = ' . $baseNamespace . ' || {};' . PHP_EOL;
        $namespaceCount = count($namespace);
        for ($i = 1; $i < $namespaceCount; $i++) {
            if ($i > 1) {
                $subNamespace = implode('.', array_slice($namespace, 0, $i));
                $jsExpression .= $subNamespace . ' = ' . $subNamespace . ' || {};' . PHP_EOL;
            }
        }
        $jsExpression .= $this->descriptor . ' = ' . $this->data . ';';

        return $this->setContent($jsExpression);
    }
}
