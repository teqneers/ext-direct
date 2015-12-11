<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 11.12.15
 * Time: 14:26
 */

namespace TQ\ExtDirect\Service;

/**
 * Class CachedServiceLoader
 *
 * @package TQ\ExtDirect\Service
 */
class CachedServiceLoader implements ServiceLoader
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var ServiceLoader
     */
    private $innerLoader;

    /**
     * @param string        $dir
     * @param ServiceLoader $innerLoader
     */
    public function __construct($dir, ServiceLoader $innerLoader)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $dir));
        }
        if (!is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is not writable.', $dir));
        }

        $this->dir         = rtrim($dir, '\\/');
        $this->innerLoader = $innerLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $classes = $this->loadFromCache();
        if ($classes === null) {
            $classes = $this->innerLoader->load();
            $this->saveInCache($classes);
        }
        return $classes;
    }

    /**
     * @return string
     */
    private function getCacheFile()
    {
        return $this->dir . '/' . strtr(__CLASS__, '\\', '-') . '.cache.php';
    }

    /**
     * @return array|null
     */
    private function loadFromCache()
    {
        $path = $this->getCacheFile();
        if (!file_exists($path)) {
            return null;
        }

        return include $path;
    }

    /**
     * @param array $classes
     */
    private function saveInCache(array $classes)
    {
        $tmpFile = tempnam($this->dir, 'service-cache');
        file_put_contents($tmpFile, '<?php return unserialize(' . var_export(serialize($classes), true) . ');');
        chmod($tmpFile, 0666 & ~umask());

        $this->renameFile($tmpFile, $this->getCacheFile());
    }

    /**
     * @param string $source
     * @param string $target
     */
    private function renameFile($source, $target)
    {
        if (false === @rename($source, $target)) {
            if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                if (false === copy($source, $target)) {
                    throw new \RuntimeException(sprintf('(WIN) Could not write new cache file to %s.', $target));
                }
                if (false === unlink($source)) {
                    throw new \RuntimeException(sprintf('(WIN) Could not delete temp cache file to %s.', $source));
                }
            } else {
                throw new \RuntimeException(sprintf('Could not write new cache file to %s.', $target));
            }
        }
    }
}
