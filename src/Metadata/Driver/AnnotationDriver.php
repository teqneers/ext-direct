<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.12.15
 * Time: 14:33
 */

namespace TQ\ExtDirect\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use TQ\ExtDirect\Annotation\Parameter;
use TQ\ExtDirect\Annotation\Result;
use TQ\ExtDirect\Metadata\ActionMetadata;
use TQ\ExtDirect\Metadata\MethodMetadata;

/**
 * Class AnnotationDriver
 *
 * @package TQ\ExtDirect\Metadata\Driver
 */
class AnnotationDriver implements DriverInterface
{
    const ACTION_ANNOTATION_CLASS = 'TQ\ExtDirect\Annotation\Action';
    const METHOD_ANNOTATION_CLASS = 'TQ\ExtDirect\Annotation\Method';

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
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
            $actionMetadata->serviceId = $actionAnnotation->serviceId ?: null;
            $actionMetadata->alias     = $actionAnnotation->alias ?: null;
        } else {
            return null;
        }

        $methodCount = 0;
        foreach ($class->getMethods() as $method) {
            if (!$method->isPublic()) {
                continue;
            }

            $methodMetadata = $this->loadMetadataForMethod($class, $method);
            if ($methodMetadata) {
                $actionMetadata->addMethodMetadata($methodMetadata);
                $methodCount++;
            }
        }

        if ($methodCount < 1) {
            return null;
        }

        return $actionMetadata;
    }

    /**
     * @param \ReflectionClass  $class
     * @param \ReflectionMethod $method
     * @return null|MethodMetadata
     */
    private function loadMetadataForMethod(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $methodAnnotation = $this->reader->getMethodAnnotation($method, self::METHOD_ANNOTATION_CLASS);
        if ($methodAnnotation === null) {
            return null;
        }

        /** @var \TQ\ExtDirect\Annotation\Method $methodAnnotation */
        $methodMetadata                 = new MethodMetadata($class->name, $method->name);
        $methodMetadata->isMethod       = true;
        $methodMetadata->isFormHandler  = $methodAnnotation->formHandler;
        $methodMetadata->hasNamedParams = $methodAnnotation->namedParams;
        $methodMetadata->isStrict       = $methodAnnotation->strict;
        $methodMetadata->addParameters($method->getParameters());

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Parameter) {
                if (!empty($annotation->constraints)) {
                    $methodMetadata->addParameterMetadata(
                        $annotation->name,
                        $annotation->constraints,
                        $annotation->validationGroups,
                        $annotation->strict,
                        $annotation->serializationGroups,
                        $annotation->serializationAttributes,
                        $annotation->serializationVersion
                    );
                }
            } elseif ($annotation instanceof Result) {
                $methodMetadata->setResult(
                    $annotation->groups,
                    $annotation->attributes,
                    $annotation->version
                );
            }
        }

        return $methodMetadata;
    }
}
