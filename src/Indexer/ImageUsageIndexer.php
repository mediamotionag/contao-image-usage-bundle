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
	
		\System::getContainer()
		      ->get('monolog.logger.contao')
		      ->log(LogLevel::INFO, 'index action', array(
		      'contao' => new ContaoContext(__CLASS__.'::'.__FUNCTION__, TL_GENERAL
		      )));

		foreach ($this->indexers as $indexer) {
			$indexer->index($document);
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
