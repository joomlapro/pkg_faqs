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

// Load the caption behavior script.
JHtml::_('behavior.caption');
?>
<div class="blog<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')): ?>
		<h2>
			<?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')): ?>
				<span class="subheading-category"><?php echo $this->category->title; ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>
	<?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)): ?>
		<?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)): ?>
		<div class="category-desc clearfix">
			<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')): ?>
				<img src="<?php echo $this->category->getParams()->get('image'); ?>" />
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->category->description): ?>
				<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_FAQS.category'); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)): ?>
		<?php if ($this->params->get('show_no_questions', 1)): ?>
			<p><?php echo JText::_('COM_FAQS_NO_QUESTIONS'); ?></p>
		<?php endif; ?>
	<?php endif; ?>
	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)): ?>
		<div class="items-leading clearfix">
			<?php foreach ($this->lead_items as &$item): ?>
				<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
	$introcount = (count($this->intro_items));
	$counter = 0;
	?>
	<?php if (!empty($this->intro_items)): ?>
		<?php foreach ($this->intro_items as $key => &$item): ?>
			<?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
			<?php if ($rowcount == 1): ?>
				<?php $row = $counter / $this->columns; ?>
				<div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-'.$row; ?> row-fluid clearfix">
			<?php endif; ?>
			<div class="span<?php echo round((12 / $this->columns)); ?>">
				<div class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<?php $counter++; ?>
			</div>
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)): ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if (!empty($this->link_items)): ?>
		<div class="items-more">
			<?php echo $this->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0): ?>
		<div class="cat-children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1): ?>
				<h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>
	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)): ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)): ?>
				<p class="counter pull-right"><?php echo $this->pagination->getPagesCounter(); ?></p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>
