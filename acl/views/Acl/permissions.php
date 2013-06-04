<?php new AjaxContentView('Acl Permissions');
HBreadcrumbs::set(array('Acl Groups'=>'/acl'),'Permissions for "'.AclGroup::findValueNameById($groupId).'"')
 ?>
<?php $form=HForm::create(null,array('action'=>'#','onsubmit'=>'return false')) ?>
{f $groups as $group=>$permissions}
	<fieldset class="clearfix">
		<legend>{$group}</legend>
		<ul{if $group!=='No group'} rel="{$group}"{/if} class="mosaic">
			{f $permissions as $permission}
				<li>
					<input type="checkbox" id="Input{$permission}" name="{$permission}"{if isset($perms[$permission]) && $perms[$permission]!==null} checked="checked"{/if}/>
					 <label for="Input{$permission}">{$permission}</label>
				</li>
			{/f}
		</ul>
	</fieldset>
	<br/>
{/f}
{=$form->end(false)}
<?php HHtml::jsReady('$("input:checkbox").change(function(){var $t=$(this);'
					.'$.get(baseUrl+"acl/update/'.$groupId.'",{perm:$t.attr("name"),value:$t.is(":checked") ? 1 : 0})})') ?>
