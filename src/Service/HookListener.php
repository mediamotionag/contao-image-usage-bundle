<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Service;

use Contao\Image;
use Contao\File;
use Memo\ImageUsageBundle\Model\AssetsModel;

class HookListener
{

	public function logImageResizes(string $originalPath, int $width, int $height, string $mode, string $cacheName, File $file, string $targetPath, Image $imageObject)
	{
		dump($file);
		dump($mode);
		dump($imageObject);
		dump($targetPath);
		
		if($cacheName != '' && $file){
			
			if($objOriginal = \FilesModel::findByPath($originalPath)){

				if(!$objAsset = AssetsModel::findBy(array('file=?', 'asset=?'), array($objOriginal->uuid, $cacheName))){
					
					$objAsset = new AssetsModel();
					$objAsset->file = $objOriginal->uuid;
					$objAsset->file_id = $objOriginal->id;
					$objAsset->name = $objOriginal->name;
					$objAsset->width = $width;
					$objAsset->height = $height;
					$objAsset->asset = $cacheName;
					$objAsset->tstamp = time();
					$objAsset->save();
					
				}
				
			}
		}
		
		return false;
	}

}
