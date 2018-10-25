<?php

declare(strict_types=1);

namespace MsgPhp\Domain\Infra\Doctrine;

/**
 * @author Pascal Wacker <hello@pascalwacker.ch>
 *
 * @internal
 */
final class MappingConfig
{
    public const DEFAULT_KEY_MAX_LENGTH = 191;

    /** @var string[] */
    public $mappingFiles;
    /** @var string|null */
    public $mappingDir;
    /** @var int */
    public $keyMaxLength = self::DEFAULT_KEY_MAX_LENGTH;

    public function __construct(array $mappingConfig = [])
    {
        $this->mappingFiles = (array) ($mappingConfig['mapping_files'] ?? []);
        $this->mappingDir = (string) ($mappingConfig['mapping_dir'] ?? null);
        $this->keyMaxLength = (int) ($mappingConfig['key_max_length'] ?? self::DEFAULT_KEY_MAX_LENGTH);
    }

    public static function fromArray(array $mappingConfig = []): self
    {
        return new self($mappingConfig);
    }

    /**
     * Replaces config values in template and returns it.
     */
    public function interpolate(string $contents): string
    {
        return str_replace('%msgphp.doctrine.mapping_config.key_max_length%', (string) ($this->keyMaxLength ?? self::DEFAULT_KEY_MAX_LENGTH), $contents);
    }

    // Option to interpolate with all the classes properties
    // call in interpolate() as:
    // $contents = $this->_interpolate($contents, $this);
//
//    /**
//     * Interpolate handler
//     *
//     * @param string $contents
//     * @param $subject
//     * @param string $parentKey
//     * @return string
//     */
/*    protected function _interpolate(string $contents, $subject, string $parentKey = ''): string
    {
        // handle properties
        foreach ($subject as $key => $value) {
            // from cammel to snake case
            $key = preg_replace_callback('/[A-Z][A-Z]*//*', function($match) { return '_'.strtolower(array_shift($match)); }, $key);
            if (is_numeric($value) || is_string($value)) {
                $contents = str_replace('%msgphp.doctrine.mapping_config.'.$parentKey.$key.'%', $value, $contents);
            } elseif (is_array($key)) {
                $contents = $this->_interpolate($contents, $value, $parentKey.'.'.$key);
            }
        }

        return $contents;
    }*/

    // Init all properties from an array and throw an Exception if they aren't defined
//
//    /**
//     * Init the DTO with default values and config values
//     *
//     * @param array $mappingConfig
//     * @param string $projectDir
//     */
/*    protected function _init(array $mappingConfig, string $projectDir): void
    {
        // default values
        $this->mappingDir = $projectDir.'/config/packages/msgphp/doctrine';
        $this->keyMaxLength = self::DEFAULT_KEY_MAX_LENGTH;

        // set class properties
        foreach ($mappingConfig as $key => $value) {
            // from snake to camel case (found at: https://stackoverflow.com/a/2792045/2989952)
            $property = lcfirst(str_replace('_', '', ucwords($key, '_')));
            if (!property_exists(__CLASS__, $property)) {
                throw new \InvalidArgumentException('Property '.$key.' not found in MsgPhp!');
            }

            $this->$property = $value;
        }
    }*/
}
