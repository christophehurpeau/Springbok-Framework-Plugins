includeCore('springbok.jqueryui');

_.posts={
	getGallery:function(){
		if(this.gallery!==undefined) return this.gallery;
		return this.gallery=new Gallery($('<div id="PostsGallery" style="width:800px;height:600px;margin-right:20px"/>'),
										basedir+'postsAlbum',function(id){return webdir+'files/images/'+id+'-small.jpg';});
	},
	edit:function(postId){
		S.ready(function(){
			$("#editTabs").tabs();
			
			var internalLinks={
				article:{
					title:'Article',params:{id:{title:'Id',style:'width:45px'}},search:{title:'Nom de l\'article (Recherche)'},
					checkParam:basedir+'posts/checkId',
					autocomplete:{
						source:basedir+'posts/autocomplete',
						focus:function(){return false;},
						select:function(event,ui){
							var div=$(this).closest('div.ui-dialog-content');
							div.find('#InputArticle_id').val(ui.item.id).change();
							div.find('#InputHref').val(ui.item.url).prop('disabled',true);
						}
					}
				}
			};
			S.tinymce.init("100%","150px",'basicAdvanced',true).addAttr("gallery",_.posts.getGallery()).wordCount().autolink().autoSave().validXHTML()
				.addAttr('onchange_callback',function(inst){$('#PostMeta_descrAuto').val(inst.getBody().innerHTML.sbStripTags()).change()})
				.addAttr('internalLinks',internalLinks)
				.createForIds("PostExcerpt");
			S.tinymce.init("100%","430px",'basicAdvanced',true)
				.addAttr("gallery",_.posts.gallery)
				.addAttr('internalLinks',internalLinks)
				.wordCount().autolink().autoSave().validXHTML().createForIds("PostContent");
			$("#formPostEdit").ajaxForm(basedir+'posts/save/'+postId,false,function(){
				if($("#PostContent").val()=="" || $("#PostExcerpt").val()==""){alert("Le texte est vide !");return false;}
			});
			$('#PostTitle').change(function(){
				var val=$(this).val();
				$('#PostSlugAuto').val(val.sbSlug()).change();
				$('#PostMeta_titleAuto').val(val).change();
			});
			$('#PostTags ul').change(function(){
				$('#PostMeta_keywordsAuto').val($(this).find('li span').map(function(){return $(this).text()}).get().sort().join(', '));
			});
			
			['Slug','Meta_title','Meta_descr','Meta_keywords'].sbEach(function(i,m){
				var input=$('#Post'+m), val=input.val(), tr=input.closest('tr'),
					mw=tr.find('.manuel .words'), mc=tr.find('.manuel .chars');
				input.change(function(){
					var val=$(this).val();
					mw.text(val.sbWordsCount());
					mc.text(val.length);
				});
				mw.text(val.sbWordsCount());
				mc.text(val.length);
				
				input=$('#Post'+m+'Auto'); val=input.val(); tr=input.closest('tr');
				var aw=tr.find('.auto .words'), ac=tr.find('.auto .chars');
				input.change(function(){
					var val=$(this).val();
					aw.text(val.sbWordsCount());
					ac.text(val.length);
				});
				aw.text(val.sbWordsCount());
				ac.text(val.length);
			});
		});
	},
	meta:function(t){
		var tr=$(t).closest('tr');
		if(tr.hasClass('auto')){
			tr.removeClass('auto').addClass('manuel');
			var input=tr.find('input.manuel').prop('disabled',false);
			if(input.hasClass('autoOnLoad'))
				input.removeClass('autoOnLoad').val(tr.find('input.auto').val());
			tr.find('a').text('Manuel');
		}else{
			tr.removeClass('manuel').addClass('auto');
			var input=tr.find('input.manuel').prop('disabled',true);
			if(input.val()===tr.find('input.auto').val()) input.addClass('autoOnLoad');
			tr.find('a').text('Automatique');
		}
		return false;
	},
	
	
	linkedPosts:function(postId){
		$('#PostLinkedPostAdd').autocomplete({
			source:basedir+'postPosts/autocomplete/'+postId,
			focus:function(event,ui){return false;},
			select:function(event,ui){
				$.get(basedir+'postPosts/add/'+postId+'/'+ui.item.id,function(result){
					if(result==='1'){
						var ul=$('#PostLinkedPosts ul');
						if(ul.length===0) ul=$('<ul class="compact"/>').appendTo($('#PostLinkedPosts').empty());
						ul.append(
							$('<li/>').addClass(!ui.item.pblsd?'italic':'').text(ui.item.id+' : '+ui.item.value+' ').append(S.html.iconAction('delete','#',{'onclick':'return _.posts.delLinked(this,'+postId+','+ui.item.id+')'}))
							.hide().css('opacity',0).delay(100).animate({opacity:1,height:'toggle'},function(){$(this).css('display','')})
						);
					}
				});
				$(this).val('');
				return false;
			}
		});
	},
	
	delLinked:function(t,postId,linkedPostId){
		var th=this;
		$.get(basedir+'postPosts/delete/'+postId+'/'+linkedPostId,function(result){
			th.updatePostsLinkedList(result,t,'PostLinkedPostsDeleted','delLinked','undelLinked');
		});
		return false;
	},
	undelLinked:function(t,postId,linkedPostId){
		var th=this;
		$.get(basedir+'postPosts/undelete/'+postId+'/'+linkedPostId,function(result){
			th.updatePostsLinkedList(result,t,'PostLinkedPosts','undelLinked','delLinked');
		});
		return false;
	},
	
	updatePostsLinkedList:function(result,t,newDivId,oldFunction,newFunction){
		if(result==='1' || result==='2'){ // 2 : dé-supprimé mais n'existe plus dans la liste des articles
			t=$(t);
			var li=t.closest('li'), oldUl=li.parent();
			
			li.animate({opacity:0,height:'toggle'},function(){
				li.remove(); 
				if(result==='1'){
					var ul=$('#'+newDivId+' ul');
					if(ul.length===0) ul=$('<ul class="compact"/>').appendTo($('#'+newDivId).empty());
					t.attr('onclick',t.attr('onclick').replace(oldFunction,newFunction)); li.appendTo(ul).animate({opacity:1,height:'toggle'});
				}
				if(oldUl.find('li').length===0) oldUl.parent().html('<div class="italic">Aucun</div>');
			})
		}
	}
};
