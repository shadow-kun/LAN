<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerDefault extends JControllerBase
	{
		/**
		 * Method to execute the controller.
		 *
		 * @return  void
		 *
		 * @since   12.1
		 * @throws  RuntimeException
		 */
		public function execute()
		{
			 // Get the application
			$app = $this->getApplication();
			 
			// Get the document object.
			$document = $app->getDocument();
			 
			$viewName = $app->input->getWord('view', 'event');
			$viewFormat = $document->getType();
			$layoutName = $app->input->getWord('layout', 'default');
			
			
			$app->input->set('view', $viewName);
			 
			// Register the layout paths for the view
			$paths = new SplPriorityQueue;
			$paths->insert(JPATH_COMPONENT . '/views/' . $viewName . '/tmpl', 'normal');
			 
			$viewClass = 'EventsViews' . ucfirst($viewName) . ucfirst($viewFormat);
			$modelClass = 'EventsModels' . ucfirst($viewName);
			 
			if (false === class_exists($modelClass))
			{
				// Need to change this when the default view is changed
				$modelClass = 'EventsModelsEvent';
			}
			 
			$view = new $viewClass(new $modelClass, $paths);
			$view->setLayout($layoutName);
			 
			// Render our view.
			echo $view->render();
			 
			return true;
		}
	}
	
?>
	