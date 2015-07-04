<?php
/**
*
* New Topic [English]
*
* @package info_acp_newtopic.php
* @copyright BB3.MOBi (c) 2015 Anvar http://apwa.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

$lang = array_merge($lang, array(
	'ACP_NEWTOPIC'					=> 'Quickly create topic',
	'ACP_NEWTOPIC_FORUM'			=> 'Forum selection',
	'ACP_NEWTOPIC_FORUM_EXPLAIN'	=> 'Select the forum that will be displayed in the drop-down list when you create a new topic.<br />Select multiple forums by holding <samp>CTRL</samp> and clicking.',
	'ACP_NEWTOPIC_BUTTON'			=> 'Name of button, replacement of "New Topic"',
));
