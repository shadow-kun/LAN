<?php
	// No direct access to this file
	defined('_JEXEC') or die;
	 
	// import the list field type
	jimport('joomla.form.helper');
	JFormHelper::loadFieldClass('list');
	 
	/**
	 * Events Form Field class for the Events component
	 */
	class JFormFieldEvents extends JFormFieldList
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
		public function getOptions() 
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,title');
			$query->from('#__events_events');
			$query->order('id' . ' DESC');
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