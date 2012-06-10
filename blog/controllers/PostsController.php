<?php
class PostControllers extends AController{
	/** */
	function index(){
		set('posts',CPagination::create(Post::QAll()->fields('id,title,slug,intro,created,published,updated')
						->with('PostImage','image_id')->orderByCreated())->pageSize(10)->execute());
		render();
	}
}