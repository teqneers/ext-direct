<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.12.15
 * Time: 14:43
 */

namespace TQ\ExtDirect\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;

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
        $this->classes = array_unique(array_merge($this->classes, $classes));
        $this->clearAllClassNames();
    }

    /**
     * @param string $class
     */
    public function addClass($class)
    {
        $this->addClasses([$class]);
    }

    /**
     * {@inheritdoc}
     */
    protected function findAllClassNames()
    {
        return array_filter(
            $this->classes,
            function ($class) {
                return $this->isActionClass($class);
            }
        );
    }
}
