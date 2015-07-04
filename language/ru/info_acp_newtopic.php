<?php
/**
*
* New Topic [Russian]
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
	'ACP_NEWTOPIC'					=> 'Быстрое создание тем',
	'ACP_NEWTOPIC_FORUM'			=> 'Форум для выбора',
	'ACP_NEWTOPIC_FORUM_EXPLAIN'	=> 'Выберите форумы которые будут отображаться в выпадающем списке при создании новой темы.<br />Чтобы выбрать несколько форумов, пользуйтесь мышью, удерживая кнопку <samp>CTRL</samp>.',
	'ACP_NEWTOPIC_BUTTON'			=> 'Название кнопки, замена "Новая тема"',
));
