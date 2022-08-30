# Smile Lab PHPCS Coding Standard

## Description

This coding standard is meant to be used on Magento projects and modules.

It uses the following rulesets:

- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Magento Coding Standard](https://github.com/magento/magento-coding-standard)
- [Slevomat Coding Standard](https://github.com/slevomat/coding-standard)

## Installation

To use this ruleset, require it in composer:

```
composer require --dev smile/magento2-smilelab-phpcs
```

## Configuration

Create a configuration file named`phpcs.xml.dist` at the root of your project.

Example for a Magento module:

```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

    <arg name="basepath" value="."/>
    <arg name="extensions" value="php,phtml"/>
    <arg name="colors"/>

    <!-- Show progress of the run -->
    <arg value="p"/>

    <!-- Show sniff codes -->
    <arg value="s"/>

    <rule ref="SmileLab"/>

    <exclude-pattern>vendor/*</exclude-pattern>
</ruleset>
```

Example for a Magento project:

```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

    <arg name="basepath" value="."/>
    <arg name="extensions" value="php,phtml"/>
    <arg name="colors"/>

    <!-- Show progress of the run -->
    <arg value="p"/>

    <!-- Show sniff codes -->
    <arg value="s"/>

    <rule ref="SmileLab"/>

    <file>app/code</file>
</ruleset>
```

## Usage

You can run phpcs with this command:

```bash
php vendor/bin/phpcs -s --extensions=php,phtml
```

You can fix most of the errors found with:

```bash
php vendor/bin/phpcbf --extensions=php,phtml
```
