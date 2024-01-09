<?php

namespace Unicon\Yaml;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConstructorParamNotSet;
use Unicon\Unicon\Errors\DynamicPropertyError;
use Unicon\Unicon\Errors\EmptyArrayError;
use Unicon\Unicon\Errors\KeyDuplicationError;
use Unicon\Unicon\Errors\TooLargeError;
use Unicon\Unicon\Errors\TooSmallError;
use Unicon\Unicon\Errors\TrueFalseError;
use Unicon\Unicon\Errors\UnionError;
use Unicon\Unicon\Errors\ZeroError;
use Unicon\Unicon\VarPrinter;

/** @template GivenClass */
class Yaml
{
    private AbstractConverter $converter;

    /** @param class-string<GivenClass> $className */
    public function __construct(private readonly string $className)
    {
        $this->converter = ConverterFactory::create($className);
    }

    /**
     * @param string $path
     * @return GivenClass
     * @throws \Exception
     */
    public function read(string $path): mixed
    {
        $source = SymfonyYaml::parseFile($path);
        $nameConverter = new NameConverter($source);

        $result = $this->converter->convert($source);
        if ($result instanceof ConversionValue) {
            if ($result->value instanceof $this->className) {
                return $result->value;
            }

            throw new YamlException('Conversion fail');
        }

        throw new YamlException($this->createError($nameConverter, $result));
    }

    private function createError(NameConverter $nameConverter, AbstractError $error = null): string
    {
        return match (true) {
            is_null($error) => 'Yaml reading error',
            $error instanceof ConstructorParamNotSet => 'Yaml parameter '.$nameConverter->getOriginalKey([...$error->path, $error->parameter]).' is required',
            $error instanceof DynamicPropertyError =>
                'Yaml parameter '.$nameConverter->getOriginalKey([...$error->path, $error->property]).
                ' with value '.VarPrinter::print($error->value).' is unexpected',
            $error instanceof EmptyArrayError => 'Yaml array '.$nameConverter->getOriginalKey($error->path).' must not be empty',
            $error instanceof KeyDuplicationError => 'Key duplication in '.$nameConverter->getOriginalKey($error->path),
            $error instanceof TooLargeError =>
                'Yaml parameter '.$nameConverter->getOriginalKey($error->path).' must be smaller'
                .($error->mayBeEqual ? ' than ' : ' or equal to ').$error->max.
                ', '.VarPrinter::print($error->value).' given',
            $error instanceof TooSmallError =>
                'Yaml parameter '.$nameConverter->getOriginalKey($error->path).' must be greater'
                .($error->mayBeEqual ? ' than ' : ' or equal to ').$error->min.
                ', '.VarPrinter::print($error->value).' given',
            $error instanceof TrueFalseError =>
                'Yaml parameter '.$nameConverter->getOriginalKey($error->path).' must be '
                .($error->mustBe ? ' true' : ' false').
                ', '.VarPrinter::print($error->value).' given',
            $error instanceof UnionError => 'Can\'t convert '.$nameConverter->getOriginalKey($error->path).' to union '.$error->typeHint,
            $error instanceof ZeroError => 'Yaml parameter '.$nameConverter->getOriginalKey($error->path).' must not be 0',
            default => 'Can\'t convert '.$nameConverter->getOriginalKey($error->path).' to '.$error->typeHint
        };
    }
}