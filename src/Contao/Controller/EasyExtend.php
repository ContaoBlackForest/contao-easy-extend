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


class EasyExtend
{
    protected $parameters = array();

    public function init()
    {
        if (array_key_exists('TL_EXTEND', $GLOBALS)) {
            echo "";
        }
    }
}
