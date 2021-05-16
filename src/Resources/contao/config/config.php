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
array_insert($GLOBALS['BE_MOD']['system'], 1, array
(
    'assets' => array
    (
        'tables'       => array('tl_assets'),
    ),

));

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_assets']          = 'Memo\ImageUsageBundle\Model\AssetsModel';


/**
 * HOOKS
 */

/**
 * Manual Hook
 */
if($_GET['do'] == 'maintenance'){
	
	if(is_array($_POST['purge']['folders'])){

		if(in_array('images', $_POST['purge']['folders'])){
			
			// Purge tl_assets
			$objDatabase = \Database::getInstance();
			$objDatabase->prepare("TRUNCATE TABLE `tl_assets`;")->execute();
			
		}

	}
}