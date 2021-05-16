<?php

namespace Memo\CourseBundle\Model;


/**
 * Class CourseBundle
 *
 * Erweiterung der Model Klasse.
 */
class BaseModel extends \Haste\Model\Model
{
	/**
	 * Gibt für die aktuelle Sprache die Übersetzung für das entsprechende Model Item zurück.
	 * @param	$strLanguage	Sprachkürzel
	 * @return  $this			Modelobjekt
	**/
	public function getLanguageModel($strLanguage)
	{
		$strPostfix = $this->getActiveLanguagePostfix($strLanguage);
		$arrCountries = \Contao\System::getCountries();

		foreach($this->arrData as $key => $value)
		{
			$strLanguageField = $key . $strPostfix;



			if($strPostfix !== '')
			{
				if($key == 'published'){

					if (array_key_exists($strLanguageField, $this->arrData))
					{
						$this->$key = $this->$strLanguageField;
					}

				} else {

					if (array_key_exists($strLanguageField, $this->arrData) && $this->$strLanguageField !== '' && $this->$strLanguageField !== null)
					{
						$this->$key = $this->$strLanguageField;
					}

				}
			}

		}

		return $this;
	}

	/**
	  * Gibt den aktuellen Sprachen-Postfix zurück
	  * @param	$strLanguage	Sprachkürzel
	  * @return $strPostfix		Sprachabhängiger Postfix
	 **/
	public function getActiveLanguagePostfix($strLanguage)
	{
		switch ($strLanguage) {
			case "en-CH":
			case "en":
			case "en_CH":
			case "EN":
				$strLanguageFilter = 'en';
				$strPostfix = '_en';
				break;
			case "fr-CH":
			case "fr":
			case "fr_CH":
			case "FR":
				$strLanguageFilter = 'fr';
				$strPostfix = '_fr';
				break;
			case "it-CH":
			case "it":
			case "it_CH":
			case "IT":
				$strLanguageFilter = 'it';
				$strPostfix = '_it';
				break;
			default:
				$strLanguageFilter = 'de';
				$strPostfix = '';
				break;
		}
		return $strPostfix;
	}
}