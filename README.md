Common
======

[![Build Status](https://api.travis-ci.org/andytruong/yaml.svg?branch=v0.1)](https://travis-ci.org/andytruong/yaml) [![Latest Stable Version](https://poser.pugx.org/andytruong/yaml/v/stable.png)](https://packagist.org/packages/andytruong/yaml) [![Dependency Status](https://www.versioneye.com/php/andytruong:yaml/2.3.0/badge.svg)](https://www.versioneye.com/php/andytruong:yaml/2.3.0) [![License](https://poser.pugx.org/andytruong/yaml/license.png)](https://packagist.org/packages/andytruong/yaml) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/d869a5d3-e766-472d-9dd5-e2ca012b9148/mini.png)](https://insight.sensiolabs.com/projects/d869a5d3-e766-472d-9dd5-e2ca012b9148)

Simple wrapper for YAML extension, SpyC, Symfony YAML parser/dumper.

### Parser

```php
<?php
$parser = new \AndyTruong\Yaml\YamlParser();
$parser->parse($yaml_string);
```

### Dumper

```php
<?php
$parser = new \AndyTruong\Yaml\YamlDumper();
$parser->dump($php_array);
```
