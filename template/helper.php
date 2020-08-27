<?php
/**
 * @package    Joomla.Site
 * @subpackage Template.foo
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Template helper functions
 * 
 * @since   1.0.0z
 */
class tplHelperFunctions
{
	/**
	 * Method to get Template
	 *
	 * @access public
	 *
	 * @return mixed
	 * @throws Exception
	 */

	static public function template()
	{
		return Factory::getApplication()->getTemplate();
	}

	/**
	 * Method to get template path
	 *
	 * @access public
	 *
	 * @param null
	 *
	 * @return string
	 * @since 1.0
	 */
	static public function getTemplatePath()
	{
		return Uri::base(true) . '/templates/' . self::template() . '/';
	}

	/**
	 * Method to get current Page Option
	 *
	 * @access public
	 *
	 * @return mixed
	 * @since 1.0
	 */
	static public function getPageOption()
	{
		//return str_replace('_', '-', Factory::getApplication()->input->getCmd('option', ''));
		return Factory::getApplication()->input->getCmd('option', '');
	}

	/**
	 * Method to get current Page View
	 *
	 * @access public
	 *
	 * @return mixed
	 * @since 1.0
	 */
	static public function getPageView()
	{
		return Factory::getApplication()->input->getCmd('view', '');
	}

	/**
	 * Method to get current Page Layout
	 *
	 * @access public
	 *
	 * @return mixed
	 * @since version
	 */
	static public function getPageLayout()
	{
		return Factory::getApplication()->input->getCmd('layout', '');
	}

	/**
	 * Method to get current Page Task
	 *
	 * @access public
	 *
	 * @return mixed
	 * @since 1.0
	 */
	static public function getPageTask()
	{
		return Factory::getApplication()->input->getCmd('task', '');
	}

	/**
	 * Method to get the current Menu Item ID
	 *
	 * @access public
	 *
	 * @return integer
	 * @since 1.0
	 */
	static public function getItemId()
	{
		return Factory::getApplication()->input->getInt('Itemid');
	}

	/**
	 * Method to get PageClass set with Menu Item
	 *
	 * @return mixed
	 * @since  1.0
	 */
	static public function getPageClass()
	{
		$activeMenu = self::getMenuActive();
		$pageclass  = ($activeMenu) ? $activeMenu->params->get('pageclass_sfx', '') : '';

		return $pageclass;
	}

	/**
	 * Method to determine whether the current page is the Joomla! homepage
	 *
	 * @access public
	 *
	 * @return boolean
	 * @since  1.0
	 */
	static public function isHome()
	{
		// Fetch the active menu-item
		$activeMenu = self::getMenuActive();

		// Return whether this active menu-item is home or not
		return (boolean) ($activeMenu) ? $activeMenu->home : false;
	}

	/**
	 * Method to fetch the current path
	 *
	 * @access public
	 *
	 * @param   string $output Output type
	 *
	 * @return mixed
	 * @since  1.0
	 */
	static public function getPath($output = 'array')
	{
		$path = Uri::getInstance()->getPath();
		$path = preg_replace('/^\//', '', $path);

		if ($output == 'array')
		{
			$path = explode('/', $path);

			return $path;
		}

		return $path;
	}

	/**
	 * Method to get active menu item
	 *
	 * @access public
	 *
	 * @param string $type single property (see https://api.joomla.org/cms-3/classes/Joomla.CMS.Menu.MenuItem.html)
	 *
	 * @return mixed
	 * @since  1.0
	 */
	static public function getMenuActive($type = null)
	{
		$active = Factory::getApplication()->getMenu()->getActive();
		
		if (!is_null($type) && property_exists($active, $type)) {
			$active = $active->{'$type'};
		}

		return $active;
	}

	/**
	 * Generate a list of useful CSS classes for the body
	 *
	 * @access public
	 *
	 * @return boolean
	 * @since  1.0
	 */
	static public function setBodySuffix()
	{
		$classes   = array();
		$classes[] = 'option-' . self::getPageOption();
		$classes[] = 'view-' . self::getPageView();
		$classes[] = self::getPageLayout() ? 'layout-' . self::getPageLayout() : 'no-layout';
		$classes[] = self::getPageTask() ? 'task-' . self::getPageTask() : 'no-task';
		$classes[] = 'itemid-' . self::getItemId();
		$classes[] = self::getPageClass();
		$classes[] = self::isHome() ? 'path-home' : 'path-' . implode('-', self::getPath('array'));

		return implode(' ', $classes);
	}

	/**
	 * Method to manually override the META-generator
	 *
	 * @access public
	 *
	 * @param   string $generator Generator
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	static public function setGenerator($generator)
	{
		Factory::getDocument()->setGenerator($generator);
	}

	/**
	 * Method to get the current sitename
	 *
	 * @access public
	 *
	 * @return string
	 * @since  1.0
	 */
	static public function getSitename()
	{
		return Factory::getConfig()->get('sitename');
	}

	/**
	 * Method to set some Meta data
	 *
	 * @access public
	 *
	 * @return void
	 * @since  1.0
	 */
	static public function setMetadata()
	{
		$doc    = Factory::getDocument();

		$doc->setHtml5(true);
		$doc->setMetaData('X-UA-Compatible', 'IE=edge', true);
		$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
		self::setGenerator(self::getSitename());
	}

	/**
	 * Method to load CSS
	 *
	 * @access public
	 *
	 * @throws Exception
	 * @return void
	 * @since  1.0
	 */
	static public function loadCss()
	{
		HTMLHelper::_('stylesheet', 'template.css', ['version' => 'auto', 'relative' => true]);

		// Check for a custom CSS file
		$userCss = JPATH_SITE . '/templates/' . self::template() . '/css/user.css';

		if (file_exists($userCss) && filesize($userCss) > 0)
		{
			HTMLHelper::_('stylesheet', 'user.css', ['version' => 'auto', 'relative' => true]);
		}
	}

	/**
	 * Method to load JS
	 *
	 * @access public
	 *
	 * @return void
	 * @since  1.0
	 */
	static public function loadJs()
	{
		HTMLHelper::_('script', 'template.js', ['version' => 'auto', 'relative' => true], ['async' => 'async']);

		// load jquery noconflict in template.js
		$file = JURI::base(true).'/media/jui/js/jquery-noconflict.js';
		unset(JFactory::getDocument()->_scripts[$file]);
	}

	/**
	 * Method to remove scripts
	 *
	 * @access public
	 *
	 * @param mixed $type
	 *
	 * @return null
	 * @since  1.0
	 */
	static public function removeScripts($type = '')
	{
		// inline scripts
		$inline = JFactory::getDocument()->_script;

		if ( is_array($type) )
		{
			foreach ($type as $t) {
				self::removeScripts($t);
			}
		}
		else
		{
			switch ($type) {
				case 'caption':
						// remove inline script
						$pattern = '%jQuery\(window\)\.on\(\'load\'\,\s*function\(\)\s*\{\s*new\s*JCaption\(\'img.caption\'\);\s*}\s*\);\s*%';
						JFactory::getDocument()->_script = preg_replace($pattern, '', $inline);

						// remove file
						$file = JURI::base(true).'/media/system/js/caption.js';
						unset( JFactory::getDocument()->_scripts[$file] );
					break;

				case 'tooltip':
						// remove inline script
						$pattern = '%\s*jQuery\(document\)\.ready\(function\(\)\{\s*jQuery\(\'\.hasTooltip\'\)\.tooltip\(\{\"html\":\s*true,\"container\":\s*\"body\"\}\);\s*\}\);\s*%';
						JFactory::getDocument()->_script = preg_replace($pattern, '', $inline);
					break;

				case 'migrate':
						// remove file
						$file = JURI::base(true).'/media/jui/js/jquery-migrate.min.js';
						unset( JFactory::getDocument()->_scripts[$file] );

				case 'html5fallback':
						// remove file
						$file = JURI::base(true).'/media/system/js/html5fallback.js';
						unset( JFactory::getDocument()->_scripts[$file] );
					break;

				case 'jquery':
						// remove file
						$file = JURI::base(true).'/media/jui/js/jquery.min.js';
						unset( JFactory::getDocument()->_scripts[$file] );
					break;

				case 'bootstrap':
						// remove file
						$file = JURI::base(true).'/media/jui/js/bootstrap.min.js';
						unset( JFactory::getDocument()->_scripts[$file] );
					break;
				
				default:
					break;
			}
		}
	}

}
