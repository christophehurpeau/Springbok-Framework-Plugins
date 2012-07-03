includeCore('springbok.jqueryui');

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
										basedir+'cmsAlbum',function(id){return webdir+'files/cms_images/'+id+'-small.jpg';});
	},
};

_.pages={
	edit:function(postId){
		S.ready(function(){
			$("#editTabs").tabs();
			S.tinymce.init("100%","430px",'basicAdvanced',true).addAttr("gallery",_.cms.getGallery()).wordCount().autolink().autoSave().validXHTML()
				.addAttr('onchange_callback',function(inst){$('#SeoMeta_descrAuto').val(inst.getBody().innerHTML.sbStripTags()).change()})
				.addAttr('internalLinks',_.cms.internalLinks)
				.createForIds("PageContent");
			$("#formPageEdit").ajaxForm(basedir+'pages/save/'+postId,false,function(){
				if($("#PageContent").val()==""){alert("Le texte est vide !");return false;}
			});
			_.seo.init($('#PageName'));
		});
	},
};
