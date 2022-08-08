# Smile Lab PHPCS Coding Standards

These coding standards are meant to be used on any magento module made by Smile.

You can run the phpcs analysis with this command:
```bash
php vendor/bin/phpcs -s --extensions=php,phtml --standard=SmileLab --ignore="/vendor/*" [src folder]
```

Alternatively you can create a configuration file named `phpcs.xml.dist` in your project root directory:
```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

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

And run the command :
```bash
php vendor/bin/phpcs --standard=SmileLab
```

You can fix most of the errors found with:
```bash
php vendor/bin/phpcbf -s --standard=SmileLab --extensions=php,phtml --ignore="/vendor/*" [src folder]
```
