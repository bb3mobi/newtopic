<?php
/**
*
* Post new topic extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
*
* @copyright (c) 2015 Anvar <http://apwa.ru>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_NEWTOPIC'					=> 'Création rapide de sujet',
	'ACP_NEWTOPIC_FORUM'			=> 'Sélectionner des forums',
	'ACP_NEWTOPIC_FORUM_EXPLAIN'	=> 'Permet de sélectionner un ou plusieurs forums qui seront affichés dans le menu déroulant lors de la création rapide d’un nouveau sujet.<br />Sélectionner plusieurs forums en combinant la touche <samp>CTRL</samp> et le clic gauche.',
	'ACP_NEWTOPIC_BUTTON'			=> 'Nom du bouton, pour remplacer le terme « Nouveau sujet »',
));
