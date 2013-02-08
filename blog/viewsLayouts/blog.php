<?php $v=new AjaxPageView($layout_title,''); ?>
<div class="col fixed right w200">
	<div class="content">
		{if CSecure::isConnected() && CSecure::isAdmin()}
			<div class="block2 mb10">
				<h4><?= _t('Admin') ?></h4>
				{if Controller::_isset('post')}
					<?php $post=Controller::get('post'); ?>
					{link _t('plugin.blog.editPost'),'/posts/edit/'.$post->id,array('entry'=>'admin')}
				{/if}
			</div>
		{/if}
		{if!e $col_content}{=$col_content}{/if}
		<? VPostsLatestMenu::create()->render() ?>
		<? VPostsTags::create()->render() ?>
	</div>
</div>
<div class="col variable r200">
	<?php HBreadcrumbs::display(_tC('Home'),$layout_title) ?>
	{=$layout_content}
</div>