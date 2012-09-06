includeCore('springbok.jqueryui');

includeLib('codemirror/codemirror');
includeLib('codemirror/overlay');

includeLib('codemirror/modes/xml/xml');
includeLib('codemirror/modes/javascript/javascript');
includeLib('codemirror/modes/css/css');
includeLib('codemirror/modes/htmlmixed/htmlmixed');

_.cms={
	internalLinks:{
		page:{
			title:'Page',params:{id:{title:'Id',style:'width:45px'}},search:{title:'Nom de la page (Recherche)'},
			checkParam:basedir+'pages/checkId',
			autocomplete:{
				source:basedir+'pages/autocomplete',
				focus:function(){return false;},
				select:function(event,ui){
					var div=$(this).closest('div.ui-dialog-content');
					div.find('#InputPage_id').val(ui.item.id).change();
					div.find('#InputHref').val(ui.item.url).prop('disabled',true);
				}
			}
		},
	},
	
	getGallery:function(){
		if(this.gallery!==undefined) return this.gallery;
		return this.gallery=new Gallery($('<div id="CmsGallery" style="width:800px;height:600px;margin-right:20px"/>'),
										basedir+'cmsAlbum',function(id){return staticUrl+'files/cms_images/'+id+'-small.jpg';});
	},
};

_.pages={
	edit:function(postId){
		S.ready(function(){
			$("#editTabs").tabs();
			S.tinymce.init("100%","430px",'basicAdvanced',true).wordCount().autolink().autoSave().validXHTML()
				.addAttr('onchange_callback',function(inst){$('#SeoMeta_descrAuto').val(inst.getBody().innerHTML.sbStripTags()).change()})
				.createForIds("PageContent");
			$("#formPageEdit").ajaxForm(basedir+'pages/save/'+postId,false,function(){
				S.tinymce.switchtoVisual("PageContent");
				if($("#PageContent").val()==""){alert("Le texte est vide !");return false;}
			});
			_.seo.init($('#PageName'));
		});
	},
};
