<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

/**
 * Add back end modules
 */

/**
 * Add front end modules
 */

/**
 * Models
 */

/**
 * HOOKS
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('mod_imageusage.hook_listener', 'emptySearchIndexPreUsageCheck');
