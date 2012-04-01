<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Hackathon
 * @package    TemplateCleanup
 * @copyright  Copyright (c) 2012 Hackathon
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

require_once('app/Mage.php');
Mage::app('default');
Mage::getModel('hackathon_templatecleanup/observer')->fetchUsedTemplates();