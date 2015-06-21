<?php

/**
 * contaoblackforest/contao-easy-extend
 *
 * Copyright (C) ContaoBlackForest
 *
 * @package   contaoblackforest/contao-easy-extend
 * @file      config.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 ContaoBlackForest
 */

$GLOBALS['TL_HOOKS']['initializeSystem']                = array_reverse($GLOBALS['TL_HOOKS']['initializeSystem']);
$GLOBALS['TL_HOOKS']['initializeSystem']['easy-extend'] = array('Contao\EasyExtend\Controller', 'init');
$GLOBALS['TL_HOOKS']['initializeSystem']                = array_reverse($GLOBALS['TL_HOOKS']['initializeSystem']);
