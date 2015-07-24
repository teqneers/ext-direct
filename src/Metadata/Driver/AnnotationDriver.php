<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\AdvancedDriverInterface;
use Metadata\NullMetadata;
use TQ\ExtDirect\Annotation\Parameter;
use TQ\ExtDirect\Metadata\ActionMetadata;
use TQ\ExtDirect\Metadata\MethodMetadata;

/**
 * Class AnnotationDriver
 *
 * @package TQ\ExtDirect\Metadata\Driver
 */
class AnnotationDriver implements AdvancedDriverInterface
{
    const ACTION_ANNOTATION_CLASS = 'TQ\ExtDirect\Annotation\Action';
    const METHOD_ANNOTATION_CLASS = 'TQ\ExtDirect\Annotation\Method';

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    protected $paths = array();

    /**
     * @var array|null
     */
    protected $classNames;

    /**
     * @param Reader            $reader
     * @param array|string|null $paths
     */
    public function __construct(Reader $reader, $paths = null)
    {
        $this->reader = $reader;
        if ($paths) {
            $this->addPaths((array)$paths);
        }
    }

    /**
     * @param array $paths
     */
    protected function addPaths(array $paths)
    {
        $this->paths = array_unique(array_merge($this->paths, $paths));
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $actionMetadata = new ActionMetadata($class->name);

        $actionMetadata->fileResources[] = $class->getFilename();

        $actionAnnotation = $this->reader->getClassAnnotation($class, self::ACTION_ANNOTATION_CLASS);

        /** @var \TQ\ExtDirect\Annotation\Action $actionAnnotation */
        if ($actionAnnotation !== null) {
            $actionMetadata->isAction  = true;
            $actionMetadata->serviceId = $actionAnnotation->serviceId;
        } else {
            return new NullMetadata($class->name);
        }

        $methodCount = 0;
        foreach ($class->getMethods() as $reflectionMethod) {
            if (!$reflectionMethod->isPublic()) {
                continue;
            }

            $methodMetadata   = new MethodMetadata($class->name, $reflectionMethod->name);
            $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, self::METHOD_ANNOTATION_CLASS);

            /** @var \TQ\ExtDirect\Annotation\Method $methodAnnotation */
            if ($methodAnnotation !== null) {
                $methodMetadata->isMethod      = true;
                $methodMetadata->isFormHandler = $methodAnnotation->formHandler;
                $methodMetadata->addParameters($reflectionMethod->getParameters());

                foreach ($this->reader->getMethodAnnotations($reflectionMethod) as $annotation) {
                    if ($annotation instanceof Parameter) {
                        if (!empty($annotation->constraints)) {
                            $methodMetadata->addParameterConstraints(
                                $annotation->name,
                                $annotation->constraints
                            );
                        }
                    }
                }

                $actionMetadata->addMethodMetadata($methodMetadata);
                $methodCount++;
            }
        }

        if ($methodCount < 1) {
            return new NullMetadata($class->name);
        }

        return $actionMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        if ($this->classNames !== null) {
            return $this->classNames;
        }

        $classes       = array();
        $includedFiles = array();
        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw new \RuntimeException('"' . $path . '" is not a valid directory');
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+\.php$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);
                require_once $sourceFile;
                $includedFiles[] = $sourceFile;
            }
        }

        $declared = get_declared_classes();
        foreach ($declared as $className) {
            $rc         = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            if (in_array($sourceFile, $includedFiles) && $this->isActionClass($className)) {
                $classes[] = $className;
            }
        }

        $this->classNames = $classes;
        return $classes;
    }

    /**
     * @param string $className
     * @return boolean
     */
    protected function isActionClass($className)
    {
        $classAnnotations = $this->reader->getClassAnnotations(new \ReflectionClass($className));

        foreach ($classAnnotations as $annotation) {
            if (get_class($annotation) === self::ACTION_ANNOTATION_CLASS) {
                return true;
            }
        }
        return false;
    }
}
