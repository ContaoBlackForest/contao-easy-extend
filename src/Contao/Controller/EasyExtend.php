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
        return '/system/cache';
    }

    private function setCacheDir()
    {
        $contaoRoot  = $this->getContaoRoot();
        $contaoCache = $contaoRoot . $this->getCacheDir();

        if (!$this->fs->exists($contaoCache . '/bridges')) {
            try {
                $this->fs->mkdir($contaoCache . '/bridges');
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at " . $e->getPath();
            }
        }

        $this->cacheDir = $contaoCache . '/bridges';
    }

    private function loadFilesystem()
    {
        $this->fs = new Filesystem();
    }

    public function init()
    {
        if (array_key_exists('TL_EXTEND', $GLOBALS)) {
            $this->loadFilesystem();
            $this->setCacheDir();
        }
    }
}
