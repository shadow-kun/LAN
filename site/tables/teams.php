<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	class TableTeams extends JTable
	{
		/**
		* Constructor
		*
		* @param object Database connector object
		*/

		function __construct( &$db ) {

		parent::__construct('#__lan_teams', 'team_id', $db);
		}
	}
?>