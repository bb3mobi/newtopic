<?php

/**
*
* @package: Post new topic, button in forum index
* @copyright: 2015 bb3.mobi (c) Anvar (http://apwa.ru)
*
*/
namespace bb3mobi\newtopic\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected $phpbb_root_path;

	protected $php_ext;

	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request, \phpbb\template\template $template, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->auth = $auth;
		$this->db = $db;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'		=> 'post_new_topic',
			'core.modify_posting_parameters'	=> 'modify_posting',
			'core.acp_board_config_edit_add'	=> 'acp_board_config',
		);
	}

	public function post_new_topic()
	{
		$this->template->assign_vars(array(
			'U_POST_NEW_TOPIC_INDEX'	=> append_sid("{$this->phpbb_root_path}posting.$this->php_ext", 'mode=post'),
			'L_NEWTOPIC_BUTTON'			=> $this->config['newtopic_button'],
			'S_NEWTOPIC_ENABLE'			=> ($this->config['newtopic_forum']) ? true: false,
			)
		);
	}

	public function modify_posting($event)
	{
		if ($event['mode'] == 'post' && !$event['forum_id'])
		{
			$forum_ary = array();
			$forum_read_ary = $this->auth->acl_getf('f_read');
			foreach ($forum_read_ary as $forum_id => $allowed)
			{
				if ($allowed['f_read'] && $this->auth->acl_get('f_post', $forum_id))
				{
					if (!$this->exclude_forum($forum_id, $this->config['newtopic_forum']))
					{
						continue;
					}
					$forum_ary[] = (int) $forum_id;
				}
			}

			if (sizeof($forum_ary))
			{
				// Fetching topics of public forums
				$sql = 'SELECT forum_id, forum_name, forum_type FROM ' . FORUMS_TABLE . "
					WHERE " . $this->db->sql_in_set('forum_id', $forum_ary) . "
						AND forum_type != " . FORUM_LINK;
				$result = $this->db->sql_query($sql);
				$forumrow = $this->db->sql_fetchrowset($result);
				$this->db->sql_freeresult($result);

				$s_forum_options = '<select id="f" name="f" onchange="this.form.submit();">';
				foreach ($forumrow as $row)
				{
					$s_forum_options .= '<option value="' . $row['forum_id'] . '"' . (($row['forum_id'] == $forum_id) ? ' selected="selected"' : '') . '' . (($row['forum_type'] == FORUM_CAT) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . (($row['forum_type'] != FORUM_CAT) ? '&nbsp;&nbsp;' : '') . $row['forum_name'] . '</option>';

					$forum_id = ($row['forum_type'] == FORUM_POST) ? $row['forum_id'] : '';
				}
				$s_forum_options .= '</select>';

				$this->template->assign_vars(array(
					'S_FORUM_OPTIONS'	=> $s_forum_options,
					'S_FORUM_OPT_TRUE'	=> ($forum_id) ? true : false,
					)
				);

				$event['forum_id'] = $forum_id;
			}
		}
	}

	public function acp_board_config($event)
	{
		$mode = $event['mode'];
		if ($mode == 'post')
		{
			$new_config = array(
				'legend_newtopic'	=> 'ACP_NEWTOPIC',
				'newtopic_forum' => array(
					'lang' => 'ACP_NEWTOPIC_FORUM',
					'validate' => 'string',
					'type' => 'custom',
					'function' => array($this, 'select_forums'),
					'explain' => true),
				'newtopic_button' => array(
					'lang' => 'ACP_NEWTOPIC_BUTTON',
					'validate' => 'string',
					'type' => 'text:25:40',
					'explain' => false),
			);
			$search_slice = 'max_post_img_height';

			$display_vars = $event['display_vars'];
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $new_config, array('after' => $search_slice));
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);

			if ($event['submit'])
			{
				$values = $this->request->variable('newtopic_forum', array(0 => ''));
				$this->config->set('newtopic_forum', implode(',', $values));
			}
		}
	}

	// Forum Selected
	public function select_forums($value, $key)
	{
		$forum_list = make_forum_select(false, false, true, true, true, false, true);

		$selected = array();
		if(isset($this->config[$key]) && strlen($this->config[$key]) > 0)
		{
			$selected = explode(',', $this->config[$key]);
		}
		// Build forum options
		$s_forum_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($forum_list as $f_id => $f_row)
		{
			$s_forum_options .= '<option value="' . $f_id . '"' . ((in_array($f_id, $selected)) ? ' selected="selected"' : '') . (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
		}
		$s_forum_options .= '</select>';

		return $s_forum_options;
	}

	private function exclude_forum($forum_id, $forum_ary)
	{
		if ($forum_ary)
		{
			$exclude = explode(',', $forum_ary);
		}
		else
		{
			$exclude = array();
		}
		return in_array($forum_id, $exclude);
	}
}
