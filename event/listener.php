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
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected $phpbb_root_path;

	protected $php_ext;

	public function __construct(\phpbb\template\template $template, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $php_ext)
	{

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
		);
	}

	public function post_new_topic()
	{
		$this->template->assign_var('U_POST_NEW_TOPIC_INDEX', append_sid("{$this->phpbb_root_path}posting.$this->php_ext", 'mode=post'));
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

				$s_forum_options = '<select id="f" name="f">';
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
}
