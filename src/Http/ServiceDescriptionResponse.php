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
    private string $descriptor = '';

    public function __construct(
        ServiceDescription $serviceDescription,
        string $descriptor = 'Ext.app.REMOTING_API',
        int $status = 200,
        array $headers = []
    ) {
        parent::__construct($serviceDescription, $status, $headers);

        $this->setDescriptor($descriptor);
    }

    public function setDescriptor(string $descriptor): self
    {
        $this->descriptor = $descriptor;
        return $this->update();
    }

    /**
     * {@inheritdoc}
     */
    protected function update(): static
    {
        $this->headers->set('Content-Type', 'application/javascript');

        $namespace = explode('.', $this->descriptor);
        if (count($namespace) > 1) {
            $baseNamespace  = reset($namespace);
            $jsExpression   = 'var ' . $baseNamespace . ' = ' . $baseNamespace . ' || {};' . PHP_EOL;
            $namespaceCount = count($namespace);
            for ($i = 1; $i < $namespaceCount; $i++) {
                if ($i > 1) {
                    $subNamespace = implode('.', array_slice($namespace, 0, $i));
                    $jsExpression .= $subNamespace . ' = ' . $subNamespace . ' || {};' . PHP_EOL;
                }
            }
        } else {
            $jsExpression = 'var ';
        }
        $jsExpression .= $this->descriptor . ' = ' . $this->data . ';';

        return $this->setContent($jsExpression);
    }
}
