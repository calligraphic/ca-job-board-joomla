<?php
/**
 * Social Links Module for Multi Family Insiders Template
 *
 * Get count of all module positions to avoid calling methods on null objects
 *
 * @package     Calligraphic Job Board
 *
 * @version     0.1 May 1, 2018
 * @author      Calligraphic, LLC http://www.calligraphic.design
 * @copyright   Copyright (C) 2018 Calligraphic, LLC, (C) joomla-monster.com 2017
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

use \Joomla\CMS\Factory;
use \Joomla\CMS\Helper\ModuleHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Version;

// no direct access
defined('_JEXEC') or die;

$version = new Version;

$jversion = '3';

if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
	$jversion = '2.5';
}

$doc = Factory::getDocument();

$moduleId = $module->id;

$id = 'jmm-social-' . $moduleId;

$data = $params->get('items');

$json_data = ( !empty($data) ) ? json_decode($data) : false;

if ($json_data === false) {
  echo Text::_('MOD_MFI_TEMPLATE_SOCIAL_LINKS_NO_ITEMS');

	return false;
}

$field_pattern = '#^jform\[params\]\[([a-zA-Z0-9\_\-]+)\]#i';

$output_data = array();

foreach ($json_data as $item) {
  $item_obj = new stdClass();

	foreach($item as $field) {
		if (preg_match($field_pattern, $field->name, $matches)) {
      $attr = $matches[1];

			if (isset($item_obj->$attr)) {
				if (is_array($item_obj->$attr)) {
					$temp = $item_obj->$attr;
					$temp[] = $field->value;
					$item_obj->$attr = $temp;
				} else {
					$temp = array($item_obj->$attr);
					$temp[] = $field->value;
					$item_obj->$attr = $temp;
				}
			} else {
				$item_obj->$attr = $field->value;
			}
		}
	}
	$output_data[] = $item_obj;
}

$elements = count($output_data);

if( $elements < 1 ) {
	echo Text::_('MOD_MFI_TEMPLATE_SOCIAL_LINKS_NO_ITEMS');
	return false;
}

$load_fa = $params->get('load_fontawesome', 0);

if( $load_fa == 1 ) {
	$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

$theme = $params->get('theme', 1);
$theme_class = ( $theme == 1 ) ? 'default' : 'override';
$style = '';
$css = $params->get('css', '');

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 *
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
if ( !function_exists( 'color_luminance' ) ) {
	function color_luminance( $hex, $percent ) {

		// validate hex string

		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';

		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}

		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = hexdec( substr( $hex, $i*2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 );
			$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
		}

		return $new_hex;
	}
}

if( $theme == 1 ) { //default

	$i = 0;
	foreach($output_data as $item) {
		$i++;
		if( !empty($item->color) || !empty($item->color2) ) {

			$color1 = ( !empty($item->color) ) ? $item->color : $item->color2;
			$color2 = ( !empty($item->color2) ) ? $item->color2 : color_luminance($item->color, -0.1);

			$style .= '#' . $id . '.default .jmm-list li.item-' . $i . ' a .jmm-ico {'
							. 'background: ' . $color1 . ';'
							. '}'
							. '#' . $id . '.default .jmm-list li.item-' . $i . ' a:hover .jmm-ico {'
							. 'background: ' . $color2 . ';'
							. '}';
		}
  }

} else { //override

	if( !empty($css) ) {
		$i = 0;
		foreach($output_data as $item) {
			$i++;

			$color1 = ( !empty($item->color) ) ? $item->color : 'transparent';
			$color2 = ( !empty($item->color2) ) ? $item->color2 : 'transparent';

			$elcss = str_replace("%id%", '#' . $id, $css);

			$elcss = str_replace("%color%", $color1, $elcss);

			$elcss = str_replace("%color2%", $color2, $elcss);
			$elcss = str_replace("%hover%", $color2, $elcss);

			$elcss = str_replace("%item%", 'li.item-' . $i, $elcss);
			$style .= $elcss;
		}
	}
}

if ( !empty($style) )
{
	$doc->addStyleDeclaration($style);
}

$target_param = $params->get('target', 1);

$target = ( !empty($target_param) && $target_param == 1 ) ? '_blank' : '_self';

$intro_param = $params->get('intro', '');

$intro = ( !empty($intro_param) ) ? $intro_param : false;

$view = $params->get('view', '1');

$mod_class_suffix = $params->get('moduleclass_sfx', '');

require ModuleHelper::getLayoutPath('mod_mfi_template_social_links', $params->get('layout', 'default'));
