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
	public $filelist = [];
	private $framework;
	private static $hasRunOnce = false;
	public $runs = 0;
	public $linklist = [];
	public $imagelist = [];
	public $scriptlist = [];
	
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
		
		// General information
		$arrBaseURI = parse_url($node->baseURI);
		$objToolboxService = \System::getContainer()->get('memo.imageusage.toolbox');
		$strRealSourcePath = $objToolboxService->getRootPath();
		
		// Get DOM/HTML of indexable page
		libxml_use_internal_errors(true);
		$dom = new \DOMDocument;
		$dom->loadHTML($document->getBody());
		libxml_clear_errors();
		
		// Get all Links
		foreach($dom->getElementsByTagName('a') as $node){
			
			$strLinkTag = $dom->saveHTML($node);
			preg_match('/href="(.*?)"/i', $strLinkTag, $arrLink);
			$strURL = $arrLink[1];
			$arrURL = parse_url($strURL);
			parse_str($arrURL['query'], $arrParameters);
			
			// Only check a link once (less DB-calls)
			if(!in_array($arrParameters['file'], $this->linklist)){
				
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
				
				$this->linklist[] = $arrParameters['file'];
			}
		}
		
		// Get all Images
		foreach($dom->getElementsByTagName('img') as $node){
			
			$strImageTag = $dom->saveHTML($node);
			preg_match('/src="(.*?)"/i', $strImageTag, $arrImage);
			$strImagePath = $arrImage[1];
			
			// Only check a image once (less DB-calls)
			if(!in_array($strImagePath, $this->imagelist)){
				
				if($objImage = \FilesModel::findByPath($strImagePath)){
					
					$objImage->inuse = 1;
					$objImage->save();
					
				} elseif($objAsset = AssetsModel::findBy(array('asset=?'), array($strImagePath))){
					
					if($objImage = \FilesModel::findByUuid($objAsset->file)){
						
						$objImage->inuse = 1;
						$objImage->save();
						
					}
				}
				
				$this->imagelist[] = $strImagePath;
			}
		}
		
		// Get all CSS Files (for background-images, etc.)
		foreach($dom->getElementsByTagName('link') as $node){
			
			$strLinkTag = $dom->saveHTML($node);
			preg_match('/href="(.*?)"/i', $strLinkTag, $arrCSS);
			$strURL = $arrCSS[1];
			
			// Absolute urls (but local)
			if(stristr($strURL, $arrBaseURI['host'])){
				
				$strAbsoluteURL = $strURL;
				
			// External urls
			} elseif(stristr($strURL, '://')){
				
				$strAbsoluteURL = false;
			
			// Relative URL (local)
			} else{
				
				$strAbsoluteURL = $node->baseURI . ltrim($strURL, '/');
				
			}
			
			// Is it a local url?
			if($strAbsoluteURL){
				
				if(!in_array($strAbsoluteURL, $this->filelist)){
				
					// Check css-files
					if(stristr($strAbsoluteURL, '.css')){
					
						// Get css-content
						$cURLConnection = curl_init();
						curl_setopt($cURLConnection, CURLOPT_URL, $strAbsoluteURL);
						curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
						$strCSS = curl_exec($cURLConnection);
						curl_close($cURLConnection);
						
						// Look for sources
						preg_match_all('/url\(.*?\)/i', $strCSS, $arrURLs);
						
						if(is_array($arrURLs) && count($arrURLs) > 0 && count($arrURLs[0]) > 0){

							foreach($arrURLs[0] as $strSource){
								$strSource = ltrim($strSource, 'url(');
								$strSource = rtrim($strSource, ')');
								$strSource = str_replace(['"',"'"], "", $strSource);
								$strSource = ltrim($strSource, './');
								$arrSource = parse_url($strSource);
								
								$strSourcePath = $arrSource['path'];
								
								// Does the file exist?
								if(file_exists($strSourcePath)){
									
									$strRealSourcePath = realpath($strSourcePath);
									$strRealSourcePath = str_replace($strRootPath, '', $strRealSourcePath );
									
									if($objImage = \FilesModel::findByPath($strRealSourcePath)){
						
										$objImage->inuse = 1;
										$objImage->save();
										
									} elseif($objAsset = AssetsModel::findBy(array('asset=?'), array($strRealSourcePath))){
										
										if($objImage = \FilesModel::findByUuid($objAsset->file)){
											
											$objImage->inuse = 1;
											$objImage->save();
											
										}
									}

									
									
								}
								
							}
							
						}
						
					} else {
					
						$arrURL = parse_url($strAbsoluteURL);
					
						if($objImage = \FilesModel::findByPath($arrURL['path'])){
							
							$objImage->inuse = 1;
							$objImage->save();
							
						} elseif($objAsset = AssetsModel::findBy(array('asset=?'), array($arrURL['path']))){
							
							if($objImage = \FilesModel::findByUuid($objAsset->file)){
								
								$objImage->inuse = 1;
								$objImage->save();
								
							}
						}
					}
					
					$this->filelist[] = $strAbsoluteURL;
				}
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
