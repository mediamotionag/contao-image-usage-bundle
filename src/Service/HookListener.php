<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Service;

use Contao\Controller;
use Psr\Log\LogLevel;
use Contao\CoreBundle\Monolog\ContaoContext;
		
class HookListener
{	
	
	public function emptySearchIndexPreUsageCheck($arrPages, $intRoot, $isSitemap=false)
	{
		if(!$isSitemap){
			
			$objDatabase = \Database::getInstance();
			$objDatabase->prepare("UPDATE tl_files SET inuse=0 WHERE 1")->execute();
			
		}
		
		return $arrPages;
	}
}

