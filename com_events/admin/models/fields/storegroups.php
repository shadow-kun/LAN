<?php
	// No direct access to this file
	defined('_JEXEC') or die;
	 
	// import the list field type
	jimport('joomla.form.helper');
	JFormHelper::loadFieldClass('list');
	 
	/**
	 * Player Form Field class for the Events component
	 */
	class JFormFieldStoreGroups extends JFormFieldList
	{
		/**
		 * The field type.
		 *
		 * @var         string
		 */
		protected $type = 'Events';

		/**
		 * Method to get a list of options for a list input.
		 *
		 * @return      array           An array of JHtml options.
		 */
		protected function getOptions() 
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,title');
			$query->from('#__events_shop_store_groups');
			$query->where('store = ' . $db->quote(JRequest::getInt('id')));
			$db->setQuery((string)$query);
			$messages = $db->loadObjectList();
			$options = array();
			if ($messages)
			{
					foreach($messages as $message) 
					{
						$options[] = JHtml::_('select.option', $message->id, $message->title);
					}
			}
			$options = array_merge(parent::getOptions(), $options);
			return $options;
		}
	}
?>