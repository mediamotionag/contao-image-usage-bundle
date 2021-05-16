<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Indexer;

use Contao\CoreBundle\Search\Indexer\IndexerInterface;
use Contao\CoreBundle\Search\Document;
use Psr\Log\LogLevel;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\Framework\ContaoFramework;
use Memo\ImageUsageBundle\Model\AssetsModel;

class ImageUsageIndexer implements IndexerInterface
{
	/**
	 * @var array<IndexerInterface>
	 */
	private $indexers = [];
	private static $hasRunOnce = false;
	public $runs = 0;
	public $filelist = [];
	private $framework;
	
	function __construct(ContaoFramework $framework) {
		
		$this->framework = $framework;
		$this->framework->initialize();
	}
	
	public function addIndexer(IndexerInterface $indexer): self
	{
		$this->indexers[] = $indexer;
		return $this;
	}

	public function index(Document $document): void
	{	
		// Detect first run
		if($this->runs == 0){
			
			\System::log('Verwendete Bilder wurden zurückgesetzt', __METHOD__, TL_GENERAL);
			
			$objDatabase = \Database::getInstance();
			$objDatabase->prepare("UPDATE tl_files SET inuse=0 WHERE inuse=1")->execute();
			
		}
		
		// Get DOM/HTML of indexable page
		libxml_use_internal_errors(true);
		$dom = new \DOMDocument;
		$dom->loadHTML($document->getBody());
		libxml_clear_errors();
		
		// Get all Links
		$arrLinks = array();
		foreach($dom->getElementsByTagName('a') as $node){
			
			$strLinkTag = $dom->saveHTML($node);
			preg_match('/href="(.*?)"/', $strLinkTag, $arrLink);
			$strURL = $arrLink[1];
			$arrURL = parse_url($strURL);
			parse_str($arrURL['query'], $arrParameters);
			
			// Download?
			if($arrParameters['file'] != ''){
				
				if($objFile = \FilesModel::findByPath($arrParameters['file'])){
					
					$objFile->inuse = 1;
					$objFile->save();
					
				}
				
			} elseif($arrParameters['path'] != '' && $arrParameters['path'] != '#' && $arrParameters['path'] != '/') {
				
				if($objFile = \FilesModel::findByPath($arrParameters['path'])){
					
					$objFile->inuse = 1;
					$objFile->save();
					
				}
				
			}
		}
		
		// Get all Images
		foreach($dom->getElementsByTagName('img') as $node){
			
			$strImageTag = $dom->saveHTML($node);
			preg_match('/src="(.*?)"/', $strImageTag, $arrImage);
			$strImagePath = $arrImage[1];
			
			if($objImage = \FilesModel::findByPath($strImagePath)){
				
				$objImage->inuse = 1;
				$objImage->save();
				
			} elseif($objAsset = AssetsModel::findBy(array('asset=?'), array($strImagePath))){
				
				if($objImage = \FilesModel::findByUuid($objAsset->file)){
					
					$objImage->inuse = 1;
					$objImage->save();
					
				}
			} else {
				die('<pre>'.print_r($strImagePath, true) .'</pre>');
			}
			
		}
		
		// Count up Counter
		$this->runs++;
		
	}

	public function delete(Document $document): void
	{
		// Not needed
	}

	public function clear(): void
	{
		// Not needed
	}
}
