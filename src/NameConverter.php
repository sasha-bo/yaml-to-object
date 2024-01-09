<?php

namespace Unicon\Yaml;


class NameConverter
{
    /** @var array<string, string> */
    private array $camelCaseToOriginal = [];

    /**
     * @param mixed $source
     */
    public function __construct(mixed &$source)
    {
        if (is_array($source)) {
            $this->convertArray($source);
        }
    }

    /**
     * @param array<string|int> $path
     * @return string
     */
    public function getOriginalKey(array $path): string
    {
        $tail = null;
        $existing = null;
        while (count($path) > 0) {
            $existing = $this->getExistingOriginalKey($path);
            if (!is_null($existing)) {
                break;
            }
            $last = $this->convertKeyBack(array_pop($path));
            $tail = $last.(is_null($tail) ? '' : '.'.$tail);
        }

        return $existing.(!is_null($existing) && !is_null($tail) ? '.' : '').$tail;
    }

    /**
     * @param array<string|int> $path
     * @return ?string
     */
    private function getExistingOriginalKey(array $path): ?string
    {
        return $this->camelCaseToOriginal[implode('.', $path)] ?? null;
    }

    /**
     * @param array<mixed> $array
     */
    private function convertArray(array &$array, string $originalPrefix = null, string $convertedPrefix = null): void
    {
        foreach ($array as $key => $value) {
            $original = is_null($originalPrefix) ? $key : $originalPrefix.'.'.$key;
            $convertedKey = $this->convertKey($key);
            $converted = is_null($convertedPrefix) ? $convertedKey : $convertedPrefix.'.'.$convertedKey;
            $this->camelCaseToOriginal[$converted] = $original;
            if (is_array($value)) {
                $this->convertArray($value, $original, $converted);
            }
            unset($array[$key]);
            $array[$convertedKey] = $value;
        }
    }

    private function convertKey(string|int $key): string
    {
        $ret = (string) preg_replace_callback('/[A-Z]/', function(array $matches): string {
            return '_'.strtolower($matches[0]);
        }, (string) $key);
        $ret = (string) preg_replace('/[0-9]+/', '\\0_', $ret);
        $ret = (string) preg_replace('/[^a-z0-9]+/i', ' ', $ret);
        $ret = trim($ret);
        $ret = (string) preg_replace_callback('/ ([a-z])/', function(array $matches): string {
            return strtoupper($matches[1]);
        }, $ret);
        return (string) preg_replace('/[^a-z0-9]+/i', '', $ret);
    }

    private function convertKeyBack(string|int $camelCaseKey): string
    {
        $ret = (string) preg_replace('/[^a-z0-9]+/i', ' ', (string) $camelCaseKey);
        $ret = (string) preg_replace_callback('/[A-Z]/', function(array $matches): string {
            return ' '.strtolower($matches[0]);
        }, $ret);
        $ret = trim($ret);
        return (string) preg_replace('/\s+/', '_', $ret);
    }
}