<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 11.12.15
 * Time: 14:20
 */

namespace TQ\ExtDirect\Service;

/**
 * Class PathServiceLoader
 *
 * @package TQ\ExtDirect\Service
 */
class PathServiceLoader implements ServiceLoader
{
    /**
     * @var array
     */
    private $paths = array();

    /**
     * @param array $paths
     */
    public function __construct(array $paths = array())
    {
        $this->addPaths($paths);
    }

    /**
     * @param array $paths
     * @return $this
     */
    public function addPaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addPath($path)
    {
        if (!is_dir($path)) {
            throw new \RuntimeException('"' . $path . '" is not a valid directory');
        }
        $this->paths[] = $path;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        return $this->findClasses();
    }

    /**
     * {@inheritdoc}
     */
    private function findClasses()
    {
        $paths         = array_unique($this->paths);
        $classes       = array();
        $includedFiles = array();
        foreach ($paths as $path) {
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
            if (in_array($sourceFile, $includedFiles)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
