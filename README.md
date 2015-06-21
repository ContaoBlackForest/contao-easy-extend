Contao Easy Extend
==================

This module helps developer´s extend contao modules.
The magic of this module is, you can extend modules with dependencies of another module this extend the same.


System requirements
-------------------

 * Web server
 * PHP 5.3.2+ with GDlib, DOM, Phar and SOAP
 * MySQL 5.0.3+
 * contao-core >=3.2-dev,<4-dev
 * contao-community-alliance/composer-plugin ~2.0
 * symfony/filesystem ~2.0

Installation
------------

Easy to install via Composer-Package-Management in Contao-CMS.

```composer
    require contaoblackforest/contao-easy-extend
```


Usage
-----

The Usage ist very easy. You must do one in config.php in your module.

```php
    $GLOBALS['TL_EXTEND']['ExtendModule'][] = array(
        'namespace' => 'YourNamespace',
        'path'      => 'system/modules/your-module/module/ExtendModule.php'
    );
```
You replace twice "ExtendModule" with the name of the module you like to extend.
By the property namespace you must your namespace you like to use.

```
    Attention: When you using the autoload-creator
    ----------------------------------------------
    
    After compile the autoload.php you unset the class file and there namespace.
    The autoload functionality comes from this module!!!
```

This module compile to files automated.

1. The first file ist the magic of this module. 
   This file is an bridge class of the last extended there you extend. 
   After compiling you find this file in `TL_ROOT/system/cache/bridges/YourNamespabe`.
   
   Content of this file:
   ---------------------
   ```php
      <?php
      
      namespace YourNamespaceYourModuleBridge
      
      class ExtendModule extends \LastExtend\ExtendModule
      {
      }
   ```
   
   You must extend this class in your module. After extend this you have all
   extended modules in your module. 
   *In this file you don't work!!!*
   
2. The second file how comes. This will generate in your module directory.
   This helps you extend an module. When you have this file create before you
   using this module, the file don't will create. When you like allow create this file,
   you must rename your file.

   
   Content of this file:
   ---------------------
   ```php
      <?php
      
      namespace YourNamespaceYourModule
      
      class ExtendModule extends \YourNamespaceYourModuleBridge\ExtendModule
      {
      }
   ```
