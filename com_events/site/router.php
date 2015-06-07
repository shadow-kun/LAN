<?php defined( '_JEXEC' ) or die( 'Restricted access' );

   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	function EventsBuildRoute(&$query)
	{
	   $segments = array();
	   if (isset($query['view']))
	   {
				$segments[] = $query['view'];
				unset($query['view']);
	   }
	   if (isset($query['id']))
	   {
				$segments[] = $query['id'];
				unset($query['id']);
	   }
	   if (isset($query['layout']))
	   {
				$segments[] = $query['layout'];
				unset($query['layout']);
	   }
	   return $segments;
	}
	
	function EventsParseRoute($segments)
	{
       $vars = array();
	   $s = 1;
       switch($segments[0])
        {
			case 'checkin':
                $vars['view'] = 'checkin';
				$if($segments[2] === 'qrcode');
				{
					$vars['layout'] = 'qrcode';
					$id = explode(':', $segments[1]);
					$vars['id'] = (int) $id[0];
				}
                break;
		    case 'competitions':
                $vars['view'] = 'competitions';
                break;
            case 'competition':
                $vars['view'] = 'competition';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
                break;
            case 'events':
                $vars['view'] = 'events';
                break;
            case 'event':
                $vars['view'] = 'event';
				
				//Coverts the id into alias
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
                //$vars['id'] = getAlias((int) $id[0], 'event');
				
				switch($segments[2])
				{
					case 'attendees':
						$vars['layout'] = 'attendees';
						break;
					case 'confirmation':
						$vars['layout'] = 'confirmation';
						break;
					case 'prepay':
						$vars['layout'] = 'prepay';
						break;
					case 'register':
						$vars['layout'] = 'register';
						break;
					case 'unregister':
						$vars['layout'] = 'unregister';
						break;
                }
                break;
			case 'teams':
                $vars['view'] = 'teams';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				$if($segments[2] === 'myteams');
				{
					$vars['layout'] = 'myteams';
				}
                break;
            case 'team':
                $vars['view'] = 'teams';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
                switch($segments[2])
				{
					case 'add':
						$vars['layout'] = 'add';
						break;
					case 'details':
						$vars['layout'] = 'details';
						break;
					case 'myteams':
						$vars['layout'] = 'myteams';
						break;
                }
                break;
			case 'store':
                $vars['view'] = 'store';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				break;
			case 'stores':
                $vars['view'] = 'stores';
                break;
			case 'orders':
                $vars['view'] = 'store';
                $vars['layout'] = 'orders';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				break;
			
			case 'payments':
                $vars['view'] = 'store';
                $vars['layout'] = 'payments';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				break;
        }
        return $vars;
	}
	
	/*function getAlias($id = null, $view = null)
	{
		$app = JFactory::getApplication();
	   
		$table = '';
		$return = null;
	   
		switch($view)
		{
			case 'event':
				$table = '#__events_events';
				break;
			case 'team':
				$table = '#__events_teams';
				break;
			case 'competitions':
				$table = '#__events_competitions';
				break;
		}
		
		if(!empty($table))
		{
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select('alias');
			$query->from($table);
			
			// Selects current user.
			$query->where('id = ' . $db->quote($id));
							
			// Runs query
			$return = $db->setQuery($query)->loadObject();
			$db->query();
		}
		
		return $return;
	}*/
	?>