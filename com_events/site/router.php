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
	   if (isset($query['action']))
	   {
				$segments[] = $query['action'];
				unset($query['action']);
	   }
	   if (isset($query['user']))
	   {
				$segments[] = $query['user'];
				unset($query['user']);
	   }
	   // Check In Use
	   if (isset($query['event']))
	   {
				$segments[] = $query['event'];
				unset($query['event']);
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
				$vars['format'] = 'html';
				$id = explode(':', $segments[1]);
				$vars['id'] = (int) $id[0];
				
				switch($segments[2]) 
				{
					case 'confirm':
						$vars['controller'] = 'checkin';
						$vars['layout'] = 'confirm';
						$vars['format'] = 'html';
						$vars['tmpl'] = 'component';
						if($segments[2] == 'pay')
						{
							$vars['action'] = 'pay';
						}
						break;
					case 'payonly':
						$vars['controller'] = 'checkin';
						$vars['layout'] = 'payonly';
						$vars['format'] = 'html';
						$vars['tmpl'] = 'component';
						break;
					case 'qrcode':
						$vars['layout'] = 'qrcode';
						break;
					case 'search':
						$vars['controller'] = 'checkin';
						$vars['layout'] = 'search';
						$vars['format'] = 'html';
						$vars['tmpl'] = 'component';
						switch($segments[3])
						{
							case 'barcode':
								$vars['action'] = 'barcode';
								break;
							case 'registration':
								$vars['action'] = 'registration';
								break;
							case 'user':
								$vars['action'] = 'event';
								$vars['event'] = $segments[3];
								break;
						}
						break;
					default:
						$vars['layout'] = 'default';
						break;
				}
                break;
		    case 'competitions':
                $vars['view'] = 'competitions';
                break;
            case 'competition':
                $vars['view'] = 'competition';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				/*if($segments[3] == 'confirmteam' && ($segments[4] == 'success' || $segments[4] == 'failure'))
				{
					$segments[3] = $segments[4];
				}*/
				
				switch($segments[2]) 
				{
					case 'register':
						switch($segments[3]) 
						{
							case 'confirm':
								$vars['controller'] = 'register';
								$vars['layout'] = 'register';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'confirmteam':
								$vars['controller'] = 'register';
								$vars['layout'] = 'register';
								$vars['type'] = 'team';
								$vars['team'] = $segments[4];
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'register';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'register';
								$vars['result'] = 'failure';
								break;
							default:
								
								break;
						}
						break;
					case 'unregister':
						switch($segments[3]) 
						{
							case 'confirm':
								$vars['controller'] = 'unregister';
								$vars['layout'] = 'unregister';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'confirmteam':
								$vars['controller'] = 'unregister';
								$vars['layout'] = 'unregister';
								$vars['type'] = 'team';
								$vars['team'] = $segments[4];
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'unregister';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'unregister';
								$vars['result'] = 'failure';
								break;
							default:
								break;
						}
						break;
					case 'entrants':
						$vars['layout'] = 'entrants';
					default:
						break;
				}
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
						switch($segments[3]) 
						{
							case 'confirm':
								$vars['controller'] = 'confirmation';
								$vars['layout'] = 'confirmation';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'confirmation';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'confirmation';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'confirmation';
								break;
						}
						break;
					case 'prepay':
						$vars['layout'] = 'prepay';
						break;
					case 'register':
						switch($segments[3]) 
						{
							case 'confirm':
								$vars['controller'] = 'register';
								$vars['layout'] = 'register';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'register';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'register';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'register';
								break;
						}
						break;
					case 'unregister':
						switch($segments[3]) 
						{
							case 'confirm':
								$vars['controller'] = 'unregister';
								$vars['layout'] = 'unregister';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'unregister';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'unregister';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'unregister';
								break;
						}
						break;
                }
                break;
			case 'teams':
                $vars['view'] = 'teams';
                //$id = explode(':', $segments[1]);
                //$vars['id'] = (int) $id[0];
				switch($segments[1])
				{
					case 'myteams':
						$vars['layout'] = 'myteams';
						break;
					case 'create':
						switch($segments[2])
						{
							case 'confirm':
								$vars['controller'] = 'team';
								$vars['layout'] = 'create';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'create';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'create';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'create';
								break;
						}
						break;
					default:
						break;
				}	
                break;
            case 'team':
                $vars['view'] = 'team';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
                switch($segments[2])
				{
					case 'details':
						switch($segments[3])
						{
							case 'update':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'details';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'details';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'details';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'details';
								break;
						}
						break;
					case 'delete':
						switch($segments[3])
						{
							case 'confirm':
								$vars['controller'] = 'delete';
								$vars['layout'] = 'delete';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'delete';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'delete';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'delete';
								break;
						}
						break;
					case 'myteams':
						$vars['view'] = 'teams';
						$vars['layout'] = 'myteams';
						break;
					case 'leader':
						switch($segments[3])
						{
							case 'update':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'leader';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['user'] = $segments[4];
								break;
							case 'success':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'leader';
								$vars['result'] = 'success';
								break;
							case 'failure':
								$vars['layout'] = 'results';
								$vars['useraction'] = 'leader';
								$vars['result'] = 'failure';
								break;
							default:
								$vars['layout'] = 'leader';
								break;
						}
						break;
						
					case 'status':
						switch($segments[3])
						{
							case 'approve':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'approve';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['user'] = $segments[4];
								break;
							case 'reject':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'reject';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['user'] = $segments[4];
								break;
							case 'remove':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'remove';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['user'] = $segments[4];
								break;
							case 'moderator':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'update';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['action'] = 'moderator';
								$vars['user'] = $segments[4];
								break;
							case 'member':
								$vars['controller'] = 'edit';
								$vars['layout'] = 'update';
								$vars['format'] = 'html';
								$vars['tmpl'] = 'component';
								$vars['action'] = 'member';
								$vars['user'] = $segments[4];
								break;
							default:
								$vars['layout'] = 'default';
								break;
						}
						break;
					case 'register':
						$vars['controller'] = 'register';
						$vars['format'] = 'html';
						$vars['tmpl'] = 'component';
						break;
					case 'unregister':
						$vars['controller'] = 'unregister';
						$vars['format'] = 'html';
						$vars['tmpl'] = 'component';
						break;
                }
                break;
			case 'store':
                $vars['view'] = 'store';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int) $id[0];
				break;
				
			case 'adminstore':
                $vars['view'] = 'store';
                $vars['layout'] = 'adminstore';
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