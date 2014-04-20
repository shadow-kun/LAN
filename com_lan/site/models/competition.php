<?php // no direct access
 
	defined( '_JEXEC' ) or die( 'Restricted access' );
	class LANModelsCompetition extends LANModelsDefault
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function getItem()
		{
			$_competition = parent::getItem();

			return $_competition;
		}
		
		protected function _buildQuery()
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			return $query;
		}
		
		protected function _buildWhere(&$query)
		{
			
		}
	}
?>