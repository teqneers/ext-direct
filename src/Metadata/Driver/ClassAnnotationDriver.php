<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.12.15
 * Time: 14:43
 */

namespace TQ\ExtDirect\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use TQ\ExtDirect\Metadata\ActionMetadata;

/**
 * Class ClassAnnotationDriver
 *
 * @package TQ\ExtDirect\Metadata\Driver
 */
class ClassAnnotationDriver extends AnnotationDriver
{
    /**
     * @var array
     */
    private $classes = array();

    /**
     * @param Reader            $reader
     * @param array|string|null $classes
     */
    public function __construct(Reader $reader, $classes = null)
    {
        parent::__construct($reader);
        if ($classes) {
            $this->addClasses((array)$classes);
        }
    }

    /**
     * @param array $classes
     */
    public function addClasses(array $classes)
    {
        foreach ($classes as $key => $value) {
            if (is_numeric($key)) {
                $class     = $value;
                $serviceId = null;
            } else {
                $class     = $key;
                $serviceId = $value;
            }
            $this->addClass($class, $serviceId);
        }
    }

    /**
     * @param string      $class
     * @param string|null $serviceId
     */
    public function addClass($class, $serviceId = null)
    {
        $this->classes[$class] = $serviceId;
        $this->clearAllClassNames();
    }


    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $actionMetadata = parent::loadMetadataForClass($class);
        if ($actionMetadata instanceof ActionMetadata) {
            if (isset($this->classes[$class->name])) {
                $actionMetadata->serviceId = $this->classes[$class->name];
            }
        }
        return $actionMetadata;
    }

    /**
     * {@inheritdoc}
     */
    protected function findAllClassNames()
    {
        return array_filter(
            array_keys($this->classes),
            function ($class) {
                return $this->isActionClass($class);
            }
        );
    }
}
