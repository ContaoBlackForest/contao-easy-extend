<?php

/**
 * contaoblackforest/contao-easy-extend
 *
 * Copyright (C) ContaoBlackForest
 *
 * @package   contaoblackforest/contao-easy-extend
 * @file      Controller.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 ContaoBlackForest
 */


namespace Contao\EasyExtend;


class Controller
{
    protected $parameters = array();

    public function init()
    {

    }

    public function add($parameter)
    {
        array_push($this->parameters, $parameter);
    }
}
