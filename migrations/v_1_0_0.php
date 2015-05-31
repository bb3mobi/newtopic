<?php

/**
*
* @package Post new topic
* @copyright  bb3.mobi 2015 (c) Anvar [apwa.ru]
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace bb3mobi\newtopic\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['newtopic_forum']) && $this->config['newtopic_forum'];
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v313');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('newtopic_forum', '')),
			array('config.add', array('newtopic_button', '')),
		);
	}
}
