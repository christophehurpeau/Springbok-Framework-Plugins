includeCoreUtils('UString/html');
includePlugin('searchable/_admin');
includeCore('springbok.jqueryui');
includeCore('springbok.forms');
includeCore('springbok.tinymce');
includeCore('components/ImageGallery');

includeCore('codemirror/codemirror');
includeCore('codemirror/addons/search/search');
includeCore('codemirror/addons/search/match-highlighter');
includeCore('codemirror/addons/edit/matchbrackets');
includeCore('codemirror/addons/edit/closebrackets');
includeCore('codemirror/addons/edit/closetag');
includeCore('codemirror/addons/fold/foldcode');
includeCore('codemirror/addons/mode/overlay');
includeCore('codemirror/addons/hint/show-hint');

includeCore('codemirror/modes/xml/xml');
includeCore('codemirror/modes/javascript/javascript');
includeCore('codemirror/addons/hint/javascript-hint');
includeCore('codemirror/modes/css/css');
includeCore('codemirror/modes/htmlmixed/htmlmixed');
includeCore('codemirror/addons/hint/html-hint');

includeCore('codemirror/addons/fold/xml-fold');
includeCore('codemirror/addons/fold/brace-fold');


includeLib('plupload/plupload');
includeLib('plupload/plupload.html5');
includeLib('plupload/plupload.flash');
includeLib('plupload/plupload.silverlight');


includePlugin('seo/_seo');

_.cms={
	internalLinks:{
		page:{
			title:'Page',params:{id:{title:'Id',style:'width:45px'}},search:{title:'Nom de la page (Recherche)'},
			checkParam:baseUrl+'pages/checkId',
			autocomplete:{
				source:baseUrl+'pages/autocomplete',
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
		return this.gallery=new S.ImageGallery($('<div id="CmsGallery" style="width:800px;height:600px;margin-right:20px"/>'),
										baseUrl+'filesLibrary',function(id){return staticUrl+'files/library/'+id+'-small.jpg';});
	},
};

_.pages={
	edit:function(postId){
		S.ready(function(){
			$("#editTabs").tabs();
			S.tinymce.init("100%","430px",'basicAdvanced',true).wordCount().autolink().autoSave().validXHTML()
				.addAttr('onchange_callback',function(inst){$('#SeoMeta_descrAuto').val(UString.stripTags(inst.getBody().innerHTML)).change()})
				.createForIds("PageContent");
			$("#formPageEdit").ajaxForm(baseUrl+'pages/save/'+postId,false,function(){
				S.tinymce.switchtoVisual("PageContent");
				if($("#PageContent").val()==""){alert("Le texte est vide !");return false;}
			});
			_.seo.init($('#PageName'));
		});
	},
};
