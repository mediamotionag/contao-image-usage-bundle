<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

$GLOBALS['TL_DCA']['tl_files']['list']['sorting']['panelLayout'] = 'search';

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
