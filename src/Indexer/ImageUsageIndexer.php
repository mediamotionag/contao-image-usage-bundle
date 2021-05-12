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

class ImageUsageIndexer implements IndexerInterface
{
	/**
	 * @var array<IndexerInterface>
	 */
	private $indexers = [];

	public function addIndexer(IndexerInterface $indexer): self
	{
		$this->indexers[] = $indexer;

		return $this;
	}

	public function index(Document $document): void
	{	
		
		// Get DOM/HTML
		$dom = new \DOMDocument;
		$dom->loadHTML($document->getBody());
		
		// Get all Links
		$arrLinks = array();
		foreach($dom->getElementsByTagName('a') as $node){
			$arrLinks[] = $dom->saveHTML($node);
		}
		
		// Get all Images
		foreach($dom->getElementsByTagName('img') as $node){
			$strImageTag = $dom->saveHTML($node);
			preg_match('/src="(.*?)"/', $strImageTag, $arrImage);
			$strImagePath = $arrImage[1];
			if($objImage = \FilesModel::findByPath($strImagePath)){
				$objImage->inuse = 1;
				$objImage->save();
			}
			
		}
		
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
