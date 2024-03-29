# Smile Lab PHPCS Coding Standard

## Description

This coding standard is meant to be used on Magento projects and modules.

It uses the following rulesets:

- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Magento Coding Standard](https://github.com/magento/magento-coding-standard)
- [Slevomat Coding Standard](https://github.com/slevomat/coding-standard)

## Installation

To use this ruleset, require it in composer:

```shell
composer require --dev smile/magento2-smilelab-phpcs
```

## Rulesets

Two rulesets are available:

- `SmileLab` (Magento >=2.4.4)
- `SmileLab-237-243` (Magento >=2.3.7 <2.4.4)

Older versions of Magento (<2.4.4) require a separate ruleset, because they use an outdated version of the Magento coding standard.

## Configuration

Create a configuration file named`phpcs.xml.dist` at the root of your project.

Example for a Magento project:

```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

    <arg name="basepath" value="."/>
    <arg name="extensions" value="php,phtml"/>
    <arg name="colors"/>
    <arg value="p"/>
    <arg value="s"/>
    
    <rule ref="SmileLab"/>

    <file>app/code</file>
    <file>app/design</file>
</ruleset>
```

Example for a community module:

```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

    <arg name="basepath" value="."/>
    <arg name="extensions" value="php,phtml"/>
    <arg name="colors"/>
    <arg value="p"/>
    <arg value="s"/>

    <config name="php_version" value="{{min_php_version}}"/>

    <rule ref="SmileLab"/>

    <file>.</file>
    <exclude-pattern>vendor/*</exclude-pattern>
</ruleset>
```

Where `{{min_php_version}}` is the minimum compatible version of PHP required by your module.
For example, if the min version is PHP 7.4:

```xml
<config name="php_version" value="70400"/>
```

## Usage

You can run phpcs with this command:

```shell
vendor/bin/phpcs --extensions=php,phtml
```

You can fix most of the errors found with:

```shell
vendor/bin/phpcbf --extensions=php,phtml
```

## Guidelines

If your class overrides a method declared in a parent class, use `@inheritdoc`:

```php
/**
 * @inheritdoc
 */
public function execute(InputInterface $input, OutputInterface $output): int
{
    // ...
}
```
