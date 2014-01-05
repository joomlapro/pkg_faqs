<?php
/**
 * @package     Faqs
 * @subpackage  com_faqs
 *
 * @author      Bruno Batista <bruno.batista@ctis.com.br>
 * @copyright   Copyright (C) 2013 CTIS IT Services. All rights reserved.
 * @license     Commercial License
 */

// No direct access.
defined('_JEXEC') or die;

// Load dependent classes.
require_once __DIR__ . '/questions.php';

/**
 * Faqs Component Archive Model.
 *
 * @package     Faqs
 * @subpackage  com_faqs
 * @author      Bruno Batista <bruno.batista@ctis.com.br>
 * @since       3.2
 */
class FaqsModelArchive extends FaqsModelQuestions
{
	/**
	 * Model context string.
	 *
	 * @var     string
	 */
	public $_context = 'com_faqs.archive';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState();

		// Get the application.
		$app    = JFactory::getApplication();

		// Add archive properties.
		$params = $this->state->params;

		// Filter on archived questions.
		$this->setState('filter.state', 2);

		// Filter on month, year.
		$this->setState('filter.month', $app->input->getInt('month'));
		$this->setState('filter.year', $app->input->getInt('year'));

		// Optional filter text.
		$this->setState('list.filter', $app->input->getString('filter-search'));

		// Get list limit.
		$itemid = $app->input->get('Itemid', 0, 'int');
		$limit  = $app->getUserStateFromRequest('com_faqs.archive.list' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		$this->setState('list.limit', $limit);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query.
	 *
	 * @since   3.2
	 */
	protected function getListQuery()
	{
		// Set the archive ordering.
		$params = $this->state->params;
		$questionOrderby   = $params->get('orderby_sec', 'rdate');
		$questionOrderDate = $params->get('order_date');

		// No category ordering.
		$categoryOrderby = '';
		$secondary       = FaqsHelperQuery::orderbySecondary($questionOrderby, $questionOrderDate) . ', ';
		$primary         = FaqsHelperQuery::orderbyPrimary($categoryOrderby);

		$orderby = $primary . ' ' . $secondary . ' a.created DESC';
		$this->setState('list.ordering', $orderby);
		$this->setState('list.direction', '');

		// Create a new query object.
		$query = parent::getListQuery();

		// Add routing for archive
		// sqlsrv changes.
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('a.alias', '!=', '0');
		$case_when .= ' THEN ';
		$a_id      = $query->castAsChar('a.id');
		$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id . ' END as slug';

		$query->select($case_when);

		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias', '!=', '0');
		$case_when .= ' THEN ';
		$c_id      = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id . ' END as catslug';

		$query->select($case_when);

		// Filter on month, year.
		// First, get the date field.
		$queryDate = FaqsHelperQuery::getQueryDate($questionOrderDate);

		if ($month = $this->getState('filter.month'))
		{
			$query->where('MONTH(' . $queryDate . ') = ' . $month);
		}

		if ($year = $this->getState('filter.year'))
		{
			$query->where('YEAR(' . $queryDate . ') = ' . $year);
		}

		return $query;
	}

	/**
	 * Method to get the archived question list.
	 *
	 * @access  public
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function getData()
	{
		// Get the application.
		$app = JFactory::getApplication();

		// Lets load the content if it does not already exist.
		if (empty($this->_data))
		{
			// Get the page/component configuration.
			$params = $app->getParams();

			// Get the pagination request variables.
			$limit       = $app->input->get('limit', $params->get('display_num', 20), 'uint');
			$limitstart  = $app->input->get('limitstart', 0, 'uint');
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $limitstart, $limit);
		}

		return $this->_data;
	}

	/**
	 * JModelLegacy override to add alternating value for $odd.
	 *
	 * @param   string   $query       The query.
	 * @param   integer  $limitstart  Offset.
	 * @param   integer  $limit       The number of records.
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		// Initialiase variables.
		$result = parent::_getList($query, $limitstart, $limit);
		$odd    = 1;

		foreach ($result as $k => $row)
		{
			$result[$k]->odd = $odd;
			$odd             = 1 - $odd;
		}

		return $result;
	}
}
