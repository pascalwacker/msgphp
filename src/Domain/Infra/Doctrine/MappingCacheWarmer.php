<?php

declare(strict_types=1);

namespace MsgPhp\Domain\Infra\Doctrine;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 *
 * @internal
 */
final class MappingCacheWarmer implements CacheWarmerInterface
{
    private $rootDir;
    private $dirName;
    private $mappingFiles;

    public function __construct(string $rootDir, string $dirName, array $mappingFiles)
    {
        $this->rootDir = $rootDir;
        $this->dirName = $dirName;
        $this->mappingFiles = $mappingFiles;
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function warmUp($cacheDir): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($target = $cacheDir.'/'.$this->dirName);

        foreach ($this->mappingFiles as $file) {
            $filename = basename($file);
            $split = explode('.', $file);
            $bundle = strtolower(array_shift($split));

            if ($filesystem->exists($this->rootDir.'/config/packages/msgphp/'.$bundle.'/'.$filename)) {
                $filesystem->copy($this->rootDir.'/config/packages/msgphp/'.$bundle.'/'.$filename, $target.'/'.basename($file));
            } else {
                $filesystem->copy($file, $target.'/'.basename($file));
            }
        }
    }
}
