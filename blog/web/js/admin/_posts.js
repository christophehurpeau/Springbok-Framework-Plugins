includeCore('springbok.jqueryui');

S.extendsObj(_.cms.internalLinks,{
	post:{
		title:'Article',params:{id:{title:'Id',style:'width:45px'}},search:{title:'Nom de l\'article (Recherche)'},
		checkParam:basedir+'posts/checkId',
		autocomplete:{
			source:basedir+'posts/autocomplete',
			focus:function(){return false;},
			select:function(event,ui){
				var div=$(this).closest('div.ui-dialog-content');
				div.find('#InputPost_id').val(ui.item.id).change();
				div.find('#InputHref').val(ui.item.url).prop('disabled',true);
			}
		}
	},
	postsTag:{
		title:'Mot clé',params:{id:{title:'Id',style:'width:45px'}},search:{title:'Nom du mot clé (Recherche)'},
		checkParam:basedir+'postsTags/checkId',
		autocomplete:{
			source:basedir+'postsTags/autocomplete',
			focus:function(){return false;},
			select:function(event,ui){
				var div=$(this).closest('div.ui-dialog-content');
				div.find('#InputPostsTag_id').val(ui.item.id).change();
				div.find('#InputHref').val(ui.item.url).prop('disabled',true);
			}
		}
	},
});

_.posts={
	edit:function(postId){
		S.ready(function(){
			$("#editTabs").tabs();
			S.tinymce.init("100%","150px",'basicAdvanced',true).wordCount().autolink().autoSave().validXHTML()
				.addAttr('onchange_callback',function(inst){$('#SeoMeta_descrAuto').val(inst.getBody().innerHTML.sbStripTags()).change()})
				.createForIds("PostExcerpt");
			S.tinymce.init("100%","430px",'basicAdvanced',true)
				.wordCount().autolink().autoSave().validXHTML().createForIds("PostContent");
			$("#formPostEdit").ajaxForm(basedir+'posts/save/'+postId,false,function(){
				if($("#PostContent").val()=="" || $("#PostExcerpt").val()==""){alert("Le texte est vide !");return false;}
			});
			_.seo.init($('#PostTitle'),$('#PostTags ul'));
		});
	},
	selectImage:function(postId){
		var g=_.cms.gallery;
		g.setOnSelectImage(function(id){
			g.close();
			$('#divPostImage').load(basedir+'posts/selectImage/'+postId+'/'+id);
		});
		g.load();
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
