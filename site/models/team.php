<?php // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
     
    class LANModelsTeam extends LANModelsDefault
    {
		/**
		*	protected Fields
		**/
		var $_team				= null;
		var $_team_id			= null;
		var $_user_id			= null;
		var $_team_name			= null;
		var $_team_captain_id	= null;
		
		function __construct()
		{
			parent::__construct();
			
			//$this->_team_id = $app->input->get('_team_id',null);
		}
		
		function getItem()
		{
			$_team_id = parent::getItem();

			return $_team_id;
		}
  
		protected function _buildQuery()
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
 
			$query->select('t.team_id, t.team_name, t.event_id, t.team_description');
			$query->from('#__lan_teams as t');
 
			/*$ query->select('w.waitlist_id');
			 * $query->leftjoin('#__lendr_waitlists as w on w.book_id = b.book_id'); */
			 
			return $query;
		}
 
		protected function _buildWhere(&$query)
		{
			if(is_numeric($this->_team_id))
			{
				$query->where('t.team_id = ' . (int) $this->_team_id);
			}
			
			if(is_numeric($this->_user_id))
			{
				$query->where('t.user_id = ' . (int) $this->_user_id);
			}
			
			// Not 100% on this, test plz
			if(!($this->_team_name))
			{
				$query->where('t.team_name like "%' . $this->_team_name . '%"');
			}
			
			$query->group("t.team_name");
		}
     
		function getTeam($id)
		{
			
		}
     
    }
?>