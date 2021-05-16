<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */


/**
 * Table tl_assets
 */
$GLOBALS['TL_DCA']['tl_assets'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('name', 'file_id', 'width', 'height'),
            'flag'                    => 12,
            'panelLayout'			  => 'filter; search; limit'
        ),
        'label' => array
        (
            'fields'                  => array('asset', 'width', 'height'),
            'format'                  => '%s (%sx%s)',
            'group_callback'          => array('tl_assets', 'group_callback'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_assets']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_assets']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_assets']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{general_legend}, file, name, asset, width, height ;',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        ''                            => '',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'file_id' => array
        (
            'sql'                     => "int(10) NULL"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'file' => array
        (
            'label'               	  => &$GLOBALS['TL_LANG']['tl_assets']['file'],
            'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr', 'readonly'=>true, 'disabled'=>true),
            'filter'              	  => true,
            'sorting'                 => true,
            'sql'                     => "binary(16) NULL"
        ),
        'name' => array
        (
            'label'               	  => &$GLOBALS['TL_LANG']['tl_assets']['name'],
            'exclude'             	  => true,
            'filter'              	  => false,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'           	  => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength' => 255, 'tl_class' => 'long clr', 'readonly'=>true, 'disabled'=>true),
            'sql'                 	  => "varchar(255) NOT NULL default ''",
        ),
        'asset' => array
        (
            'label'               	  => &$GLOBALS['TL_LANG']['tl_assets']['asset'],
            'exclude'             	  => true,
            'filter'              	  => false,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'           	  => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength' => 500, 'tl_class' => 'long clr', 'readonly'=>true, 'disabled'=>true),
            'sql'                 	  => "varchar(500) NOT NULL default ''",
        ),
        'width' => array
        (
            'label'               	  => &$GLOBALS['TL_LANG']['tl_assets']['width'],
            'exclude'             	  => true,
            'filter'              	  => false,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'           	  => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength' => 30, 'tl_class' => 'w50', 'readonly'=>true, 'disabled'=>true),
            'sql'                 	  => "varchar(30) NOT NULL default ''",
        ),
        'height' => array
        (
            'label'               	  => &$GLOBALS['TL_LANG']['tl_assets']['height'],
            'exclude'             	  => true,
            'filter'              	  => false,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'           	  => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength' => 30, 'tl_class' => 'w50', 'readonly'=>true, 'disabled'=>true),
            'sql'                 	  => "varchar(30) NOT NULL default ''",
        ),
    )
);


/**
 * Class tl_assets
 * Definition der Callback-Funktionen für das Datengefäss.
 */
class tl_assets extends Backend
{

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }

    /**
     * Disable/enable a user group
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');
        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        $objVersions = new Versions('tl_assets', $intId);
        $objVersions->initialize();
        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_assets']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_assets']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, ($dc ?: $this));
                }
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_assets SET tstamp=". time() .", published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);
        $objVersions->create();
    }
    
    public function group_callback($dc, $test1, $test2, $row)
	{
		return $row['name'] . ' (ID: ' . $row['file_id'] .  ')';

	}

}