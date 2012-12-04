<?php new AjaxPageView($layout_title,'ml160') ?>


<div class="fixed left w160">
	{menuLeft 'startsWith':true/*,'menuAttributes':array('rel'=>'page')*/
		_t('plugin.cms.Pages')=>array('/pages','startsWith'=>false),
		_t('plugin.cms.HardCodedPages')=>array('/cmsHardCodedPages','startsWith'=>false),
		_t('plugin.cms.Menu')=>array('/cmsMenu'),
		false,
		_t('plugin.cms.Tools')=>'/pages/tools',
	}
</div>

<div class="variable padding">{=$layout_content}</div>