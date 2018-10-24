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
    private $dirName;
    private $mappingFiles;
    private $rootDir;

    public function __construct(string $dirName, array $mappingFiles, string $rootDir = null)
    {
        $this->dirName = $dirName;
        $this->mappingFiles = $mappingFiles;
        $this->rootDir = $rootDir;
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

            if ($this->rootDir && $filesystem->exists($this->rootDir.'/'.$filename)) {
                $filesystem->copy($this->rootDir.'/'.$filename, $target.'/'.basename($file));
            } else {
                $filesystem->copy($file, $target.'/'.$filename);
            }
        }
    }
}
