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
    private $configDir;

    public function __construct(string $dirName, array $mappingFiles, string $configDir = null)
    {
        $this->dirName = $dirName;
        $this->mappingFiles = $mappingFiles;
        $this->configDir = $configDir;
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

            if ($this->configDir && $filesystem->exists($this->configDir.'/'.$filename)) {
                $filesystem->copy($this->configDir.'/'.$filename, $target.'/'.basename($file));
            } else {
                $filesystem->copy($file, $target.'/'.$filename);
            }
        }
    }
}
