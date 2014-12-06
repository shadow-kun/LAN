<?php // no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	jimport('joomla.installer.installer');
	jimport('joomla.installer.helper');
	
	/**
    * Method to install the component
    *
    * @param mixed $parent The class calling this method
    * @return void
    */
    function install($parent)
    {
		
		JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_users/models/', 'UsersModel' );

		/******************************************************/
		/* Creates Root User Group */
		
		$groupModel = JModelLegacy::getInstance( 'Group', 'UsersModel' );

		$groupData = array(
			'title' => "LAN Party! Event Groups",
			'parent_id' => 2,
			'id' => 0 );

		$groupModel->save( $groupData );
		
		/******************************************************/
		/* Creates User Views */
		$levelModel = JModelLegacy::getInstance( 'Level', 'UsersModel' );

		$levelData = array(
			'title' => "LAN Party! Registered",
			'id' => 0 ,
			'rules' => '[8]');
			
		$levelModel->save( $levelData );	
		
		$levelData = array(
			'title' => "LAN Party! Paid",
			'id' => 0 ,
			'rules' => '[8]');
			
		$levelModel->save( $levelData );
		
		$levelData = array(
			'title' => "LAN Party! Checked-In",
			'id' => 0 ,
			'rules' => '[8]');
			
		$levelModel->save( $levelData );
		
		echo JText::_('COM_LAN_INSTALL_SUCCESSFULL');
    }
	
	/**
    * Method to update the component
    *
    * @param mixed $parent The class calling this method
    * @return void
    */
    function update($parent)
    {
		echo JText::_('COM_LAN_UPDATE_SUCCESSFULL');
    }
	
	/**
    * method to run before an install/update/uninstall method
    *
    * @param mixed $parent The class calling this method
    * @return void
    */
    function preflight($type, $parent)
    {
		
    }
     
    function postflight($type, $parent)
    {
		
    }
?>