<?php $v=new AjaxPageView($layout_title,''); ?>
<?php HBreadcrumbs::display(_tC('Home'),$layout_title) ?>
<div class="fixed right w200">
	<div class="content">
		{if CSecure::isConnected() && CSecure::isAdmin()}
			<div class="block2 mb10">
				<h4><?= _t('Admin') ?></h4>
				{if Controller::_isset('post')}
					<?php $post=Controller::get('post'); ?>
					{link _t('plugin.blog.editPost'),'/posts/edit/'.$post->id,array('fullUrl'=>Config::$admin_site_url)}
				{/if}
			</div>
		{/if}
		{if!e $col_content}{=$col_content}{/if}
		<? VPostsLatestMenu::create()->render() ?>
		<? VPostsTags::create()->render() ?>
	</div>
</div>
<div class="variable padding">{=$layout_content}</div>