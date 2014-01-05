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

// Include the component helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Load the chosen formbehavior script.
JHtml::_('formbehavior.chosen', 'select');

// Load the caption behavior script.
JHtml::_('behavior.caption');
?>
<div class="archive<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<form id="adminForm" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-inline">
		<fieldset class="filters">
			<div class="filter-search">
				<?php if ($this->params->get('filter_field') != 'hide'): ?>
					<label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_FAQS_TITLE_FILTER_LABEL') . '&#160;'; ?></label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->filter); ?>" class="inputbox span2" onchange="document.getElementById('adminForm').submit();" placeholder="<?php echo JText::_('COM_FAQS_TITLE_FILTER_LABEL'); ?>" />
				<?php endif; ?>
				<?php echo $this->form->monthField; ?>
				<?php echo $this->form->yearField; ?>
				<?php echo $this->form->limitField; ?>
				<button type="submit" class="btn btn-primary" style="vertical-align: top;"><?php echo JText::_('JGLOBAL_FILTER_BUTTON'); ?></button>
				<div>
					<input type="hidden" name="view" value="archive" />
					<input type="hidden" name="option" value="com_faqs" />
					<input type="hidden" name="limitstart" value="0" />
				</div>
			</div>
			<br />
		</fieldset>
		<?php echo $this->loadTemplate('items'); ?>
	</form>
</div>
