<?php
/**
 * @package   ImageUsageBundle
 * @author	Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Image;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\Image\DeferredResizer as ImageResizer;
use Contao\Image\DeferredImageInterface;
use Contao\CoreBundle\Image\LegacyResizer;
use Contao\Image\ImageInterface;
use Contao\Image\ResizeConfiguration;
use Contao\Image\ResizeOptions;
use Imagine\Image\ImagineInterface;
use Contao\Image\ImageDimensions;
use Contao\Image\ImportantPart;
use Contao\Image\DeferredResizerInterface;
use Memo\ImageUsageBundle\Model\AssetsModel;

class ImageResizeLogger implements DeferredResizerInterface
{
	private $parent;

	public function __construct(
		DeferredResizerInterface $parent
	) {
		$this->parent = $parent;
	}
	
	public function resize(ImageInterface $image, ResizeConfiguration $config, ResizeOptions $options): ImageInterface
	{
		// Generate the Asset
		$objReturn = $this->parent->resize($image, $config, $options);
		
		// Ger Original Image
		if($objOriginal = \FilesModel::findByPath($image->getPath())){
			
			// Check if asset is logged
			$strAsset = $objReturn->getPath();
			$strRootPath = $_SERVER['DOCUMENT_ROOT'];
			$strRootPath = preg_replace("/web$/", '', $strRootPath );
			$strAsset = str_replace($strRootPath, '', $strAsset);
			
			if(!$objAsset = AssetsModel::findBy(array('asset=?'), array($strAsset))){
				
				$objAsset = new AssetsModel();
				$objAsset->file = $objOriginal->uuid;
				$objAsset->file_id = $objOriginal->id;
				$objAsset->name = $objOriginal->name;
				$objAsset->width = $config->getWidth();
				$objAsset->height = $config->getHeight();
				$objAsset->asset = $strAsset;
				$objAsset->tstamp = time();
				$objAsset->save();
			}
		}
		return $objReturn;
	}
	
	public function getDeferredImage(string $targetPath, ImagineInterface $imagine): ?DeferredImageInterface
	{
		$objReturn =  $this->parent->getDeferredImage($targetPath, $imagine);
		return $objReturn;
	}
	
	public function resizeDeferredImage(DeferredImageInterface $image, bool $blocking = true): ?ImageInterface
	{
		$objReturn =  $this->parent->resizeDeferredImage($image, $blocking);
		return $objReturn;
	}
} 