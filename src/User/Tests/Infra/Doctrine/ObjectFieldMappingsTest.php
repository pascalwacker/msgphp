<?php

declare(strict_types=1);

namespace MsgPhp\User\Tests\Infra\Doctrine;

use MsgPhp\Domain\Infra\Doctrine\MappingConfig;
use MsgPhp\User\Entity\Features;
use MsgPhp\User\Infra\Doctrine\ObjectFieldMappings;
use PHPUnit\Framework\TestCase;

final class ObjectFieldMappingsTest extends TestCase
{
    public function testMapping(): void
    {
        $available = array_flip(array_map(function (string $file): string {
            return 'MsgPhp\\User\\Entity\\'.basename(\dirname($file)).'\\'.basename($file, '.php');
        }, glob(\dirname(__DIR__, 3).'/Entity/{Features,Fields}/*.php', \GLOB_BRACE)));
        unset(
            $available[Features\AbstractCredential::class],
            $available[Features\AbstractPasswordCredential::class],
            $available[Features\AbstractSaltedPasswordCredential::class]
        );

        $mappings = ObjectFieldMappings::provideObjectFieldMappings(new MappingConfig());
        $mappings = array_keys($mappings instanceof \Traversable ? iterator_to_array($mappings) : $mappings);
        sort($mappings);

        self::assertSame(array_keys($available), $mappings);
    }
}
