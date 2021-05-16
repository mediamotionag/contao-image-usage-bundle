<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('inuse_legend', 'title_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField('inuse', 'inuse_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_files');


$GLOBALS['TL_DCA']['tl_files']['fields']['inuse'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_files']['inuse'],
	'exclude'                 => true,
	'search'                  => true,
	'default'                 => '0',
	'inputType'               => 'checkbox',
	'eval'                    => array('mandatory'=>false, 'tl_class' => 'w50 clr'),
	'sql'                     => "char(1) NOT NULL default '0'"
];


$GLOBALS['TL_DCA']['tl_files']['list']['operations']['inuse'] = array(
	'href'                => 'act=inuse',
	'icon'                => 'root.svg',
	'button_callback'     => array('tl_files_inuse', 'inuseFile')
);


class tl_files_inuse extends Backend
{
	
	public function inuseFile($row, $href, $label, $title, $icon, $attributes)
	{
		$icon = 'root_1.svg';
		$message = "Datei wird nicht verwendet (bzw. wurde auf der öffentlich zugänglichen Webseite nicht gefunden)";
		
		if($row['type'] == 'file'){
			if($objFile = \FilesModel::findByPath($row['id'])){
			
				if($objFile->inuse == 1){
					$icon = 'root.svg';
					$message = "Datei wird verwendet";
				}
				
			}
	
			return '<a href="#" style="cursor:help;" title="'.$message.'"' . $attributes . ' onclick="event.preventDefault();return false">' . Image::getHtml($icon, $label) . '</a> ';
		} else {
			return '';
		}
		
	}
	
}