<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Service;

class Toolbox
{		
	
	/**
	 * Get Root-Path
	 */
	public function getRootPath($bolTrailingSlash = true)
	{
		$strRootPath = __DIR__;
		$strRootPath = str_replace('/src/Memo/contao-image-usage-bundle/src/Indexer', '', $strRootPath);
		$strRootPath = str_replace('/vendor/mediamotionag/contao-image-usage-bundle/src/Indexer', '', $strRootPath);
		
		if($bolTrailingSlash){
			$strRootPath .= '/';
		}
		
		return $strRootPath;
	}
	
}

?>