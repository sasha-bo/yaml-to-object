# Yaml Reader

Validates YAML file using given class and, if matches, creates 
an object. Understands PhpDoc, converts values if necessary. 
Works recursively, so the YAML file may have complicated 
structure, and the given class may have properties of other 
classes.

## Installation

```composer require unicon/yarn```

## Usage

```php
$reader = new Yaml(MyClass::class);
$object = $reader->read('my_yaml.yml');
```

## Example

```php
class Simple
{
    public int $integerParameter = 1;
    /** @var positive-int */
    public int $positiveIntegerParameter;
    public ?bool $booleanOrNullParameter;
    public string $stringParameter = 'default';
}
```

### Success example

```yaml
integer_parameter: '777'
positive_integer_parameter: 666
boolean_or_null_parameter: null
string_parameter: 888
```

### Failures

```YamlException``` is thrown is the YAML file doesn't match 
the class:

``` Yaml parameter positive_integer_parameter must be greater or equal to 1, "-1" given```

```yaml
integer_parameter: '777'
positive_integer_parameter: '-1'
boolean_or_null_parameter: null
string_parameter: 888
```
```Can't convert integer_parameter 777.777 to int```

```yaml
integer_parameter: 777.777
positive_integer_parameter: 666
boolean_or_null_parameter: null
string_parameter: 888
```
```Can't convert integer_parameter "xxx" to int```

```yaml
integer_parameter: 'xxx'
positive_integer_parameter: 666
boolean_or_null_parameter: null
string_parameter: 888
```

```Yaml parameter positive_integer_parameter is missed```

(```integer_parameter```, ```boolean_or_null_parameter``` and 
```string_parameter``` are missed too, but they have
default values or are nullable)

```yaml
string_parameter: 'aaa'
```

```Yaml parameter extra_parameter with value true is unexpected```

(this exception is never thrown for ```stdClass``` or 
`````\AllowDynamicProperties````` classes)
```yaml
extra_parameter: true
integer_parameter: '777'
positive_integer_parameter: 666
boolean_or_null_parameter: null
string_parameter: 888
```