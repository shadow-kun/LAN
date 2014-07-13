<?php
	// No direct access to this file
	defined('_JEXEC') or die;
	 
	// import the list field type
	jimport('joomla.form.helper');
	JFormHelper::loadFieldClass('list');
	 
	/**
	 * Player Form Field class for the LAN component
	 */
	class JFormFieldPlayer extends JFormFieldList
	{
		/**
		 * The field type.
		 *
		 * @var         string
		 */
		protected $type = 'LAN';

		/**
		 * Method to get a list of options for a list input.
		 *
		 * @return      array           An array of JHtml options.
		 */
		protected function getOptions() 
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,user');
			$query->from('#__lan_players');
			$db->setQuery((string)$query);
			$messages = $db->loadObjectList();
			$options = array();
			if ($messages)
			{
					foreach($messages as $message) 
					{
						$options[] = JHtml::_('select.option', $message->id, $message->user);
					}
			}
			$options = array_merge(parent::getOptions(), $options);
			return $options;
		}
	}
?>