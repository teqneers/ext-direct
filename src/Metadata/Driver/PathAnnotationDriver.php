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

/**
 * Class PathAnnotationDriver
 *
 * @package TQ\ExtDirect\Metadata\Driver
 */
class PathAnnotationDriver extends AnnotationDriver
{
    /**
     * @var array
     */
    private $paths = array();

    /**
     * @param Reader            $reader
     * @param array|string|null $paths
     */
    public function __construct(Reader $reader, $paths = null)
    {
        parent::__construct($reader);
        if ($paths) {
            $this->addPaths((array)$paths);
        }
    }

    /**
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        $this->paths = array_unique(array_merge($this->paths, $paths));
        $this->clearAllClassNames();
    }

    /**
     * @param string $path
     */
    public function addPath($path)
    {
        $this->addPaths([$path]);
    }

    /**
     * {@inheritdoc}
     */
    protected function findAllClassNames()
    {
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

        return $classes;
    }
}
