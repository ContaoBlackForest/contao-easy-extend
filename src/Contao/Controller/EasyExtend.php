<?php

/**
 * contaoblackforest/contao-easy-extend
 *
 * Copyright (C) ContaoBlackForest
 *
 * @package   contaoblackforest/contao-easy-extend
 * @file      EasyExtend.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 ContaoBlackForest
 */


namespace Contao\Controller;


use Contao\ClassLoader;
use Contao\Date;
use Contao\File;
use Contao\String;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class EasyExtend
{
    protected $parameters = array();

    protected $cacheDir;

    /** @var  $fs Filesystem */
    protected $fs;

    private function getContaoRoot()
    {
        return TL_ROOT;
    }

    private function getCacheDir()
    {
        return 'system/cache';
    }

    private function setCacheDir()
    {
        $directory = $this->getCacheDir() . '/bridges';

        if (!$this->fs->exists($this->getContaoRoot() . $directory)) {
            try {
                $this->fs->mkdir($this->getContaoRoot() . $directory);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at " . $e->getPath();
            }
        }

        $this->cacheDir = $directory;
    }

    private function loadFilesystem()
    {
        $this->fs = new Filesystem();
    }

    private function parseBridges()
    {
        foreach ($GLOBALS['TL_EXTEND'] as $module => $extends) {
            if (is_array($extends) && !empty($extends)) {
                foreach ($extends as $extend) {
                    if (is_array($extend) && !empty($extend)) {
                        $this->parseBridge($extend, $module);
                    }
                }
            }
        }
    }

    private function generateBridgeNamespace($parameters)
    {
        return $parameters['namespace'] .
               str_replace(
                   ' ',
                   '',
                   ucwords(
                       str_replace(
                           array('-', '_'),
                           array(' ', ' '),
                           explode('/', $parameters['path'])[2]
                       )
                   )
               );
    }

    private function makeBridgeDirectoryForNamespace($namespace)
    {
        $directory = $this->getContaoRoot() . '/' . $this->cacheDir . '/' . $namespace . 'Bridge';
        if (!$this->fs->exists($directory)) {
            try {
                $this->fs->mkdir($directory);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at " . $e->getPath();
            }
        }
    }

    private function getClassFromModule($class)
    {
        $namespaces = ClassLoader::getNamespaces();
        $classes    = array_reverse(ClassLoader::getClasses());

        foreach ($namespaces as $namespace) {
            if (isset($classes[$namespace . '\\' . $class])) {
                return $namespace . '\\' . $class;
            }
        }

        return null;
    }

    private function compileBridgeModule($namespace, $module, $path, $parameters)
    {
        $extends = $this->getClassFromModule($module);

        $file = new File($path, true);
        $file->truncate();

        $file->putContent(
            $path,
            '<?php ' . "\n" .
            "\n" .
            '/**' . "\n" .
            ' * This class was generate from contao-easy-extend' . "\n" .
            ' *' . "\n" .
            ' * Copyright (C) ContaoBlackForest' . "\n" .
            ' *' . "\n" .
            ' * @package   contaoblackforest/contao-easy-extend' . "\n" .
            ' * @file      ' . $module . '.php' . "\n" .
            ' * @author    Sven Baumann <baumann.sv@gmail.com>' . "\n" .
            ' * @author    Dominik Tomasi <dominik.tomasi@gmail.com>' . "\n" .
            ' * @license   GNU/LGPL' . "\n" .
            ' * @copyright Copyright ' . Date::parse('Y', time()) . ' ContaoBlackForest' . "\n" .
            ' */' . "\n" .
            "\n" .
            "\n" .
            'namespace ' . $namespace . 'Bridge;' . "\n" .
            "\n" .
            'class ' . $module . ' extends \\' . $extends . "\n" .
            '{' . "\n" . '}'
        );
    }

    private function generateBridgeModule($namespace, $module, $parameters)
    {
        $path = $this->cacheDir . '/' . $namespace . 'Bridge/' . $module . '.php';
        if (!$this->fs->exists($this->getContaoRoot() . '/' . $path)) {
            $this->compileBridgeModule($namespace, $module, $path, $parameters);
        }
    }

    private function compileModule($parameters, $bridgeNamespace, $module)
    {
        $file = new File($parameters['path'], true);
        $file->truncate();

        $file->putContent(
            $parameters['path'],
            '<?php ' . "\n" .
            "\n" .
            '/**' . "\n" .
            ' * DESCRIPTION' . "\n" .
            ' *' . "\n" .
            ' * Copyright (C) ORGANISE' . "\n" .
            ' *' . "\n" .
            ' * @package   PACKAGE NAME' . "\n" .
            ' * @file      ' . $module . '.php' . "\n" .
            ' * @author    AUTHOR' . "\n" .
            ' * @license   GNU/LGPL' . "\n" .
            ' * @copyright Copyright ' . Date::parse('Y', time()) . ' ORGANISE' . "\n" .
            ' */' . "\n" .
            "\n" .
            "\n" .
            'namespace ' . $bridgeNamespace . ';' . "\n" .
            "\n" .
            'class ' . $module . ' extends \\' . $bridgeNamespace . 'Bridge\\' . $module . "\n" .
            '{' . "\n" . '}'
        );
    }

    private function generateModule($parameters, $bridgeNamespace, $module)
    {
        if (!$this->fs->exists($this->getContaoRoot() . '/' . $parameters['path'])) {
            $this->compileModule($parameters, $bridgeNamespace, $module);
        }
    }

    private function autoload($parameters, $bridgeNamespace, $module)
    {
        ClassLoader::addNamespace($bridgeNamespace);

        ClassLoader::addClasses(
            array(
                $bridgeNamespace . '\\' . $module       => $parameters['path'],
                $bridgeNamespace . 'Bridge\\' . $module =>
                    $this->cacheDir . '/' . $bridgeNamespace . 'Bridge/' . $module . '.php'
            )
        );
    }

    private function parseBridge($parameters, $module)
    {
        $bridgeNamespace = $this->generateBridgeNamespace($parameters);
        $this->makeBridgeDirectoryForNamespace($bridgeNamespace);
        $this->generateBridgeModule($bridgeNamespace, $module, $parameters);
        $this->generateModule($parameters, $bridgeNamespace, $module);
        $this->autoload($parameters, $bridgeNamespace, $module);
    }

    public function init()
    {
        if (array_key_exists('TL_EXTEND', $GLOBALS)) {
            $this->loadFilesystem();
            $this->setCacheDir();
            $this->parseBridges();
        }
    }
}
