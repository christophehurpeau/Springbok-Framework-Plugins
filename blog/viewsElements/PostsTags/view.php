<div class="block1 mt10">
	<h2>Nuage de mots clés</h2>
	<div class="content clearfix">
		<ul class="mosaic">
			{f PostsTag::findAllSize() as $tag}<li style="font-size:{=$tag->size}pt">{link $tag->name,$tag->link()}</li>{/f}
		</ul>
	</div>
</div>