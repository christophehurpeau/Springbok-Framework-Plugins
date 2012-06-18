<h5>Articles liés</h5>
<div id="PostLinkedPosts">
	{ife $posts}<div class="italic">Aucun</div>{else}
	<ul class="compact">
		{f $posts as $post}
		<li{if !$post->isPublished()} class="italic"{/if}>{=$post->id} : {$post->name} {iconAction 'delete','#',array('onclick'=>'return _.posts.delLinked(this,'.$id.','.$post->id.')')}</li>
		{/f}
	</ul>
	{/if}
</div>

<h5 class="sepTop">Articles liés supprimés</h5>
<div id="PostLinkedPostsDeleted">
	{ife $deletedPosts}<div class="italic">Aucun</div>{else}
	<ul class="compact">
		{f $deletedPosts as $post}
		<li{if !$post->isPublished()} class="italic"{/if}>{=$post->id} : {$post->name} {iconAction 'delete','#',array('onclick'=>'return _.posts.undelLinked(this,'.$id.','.$post->id.')')}</li>
		{/f}
	</ul>
	{/if}
</div>


<h5 class="sepTop">Ajout d'un article</h5>
<input id="PostLinkedPostAdd" type="text" style="width:99%" />
<script type="text/javascript">_.posts.linkedPosts({=$id})</script>
