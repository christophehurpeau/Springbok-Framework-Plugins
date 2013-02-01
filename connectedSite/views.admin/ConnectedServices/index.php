<?php new AjaxContentView($title=_t('plugin.cms.ConnectedServices')) ?>

<h1>{$title}</h1>

<ul class="nobullets cMt10">
<?php $connectedServices=CmsConnected::QValues()->field('key'); ?>
{f AHConnectedServices::$services as $key=>$service}
	<li>
		<span class="socialicon {=$service['icon']}"></span>
			{if empty(Config::$$service['config_key'])} <i> Configure {$service['config_key']} in config</i>
			{elseif in_array($key,$connectedServices)}<i>Connected</i>
			{else} {link $service['title'],'/connectedServices/connectTo/'.$key,array('target'=>'_self')}
			{/if}
	</li>
{/f}
</ul>