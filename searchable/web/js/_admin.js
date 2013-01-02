includePlugin('seo/_seo');
includeCore('ui/dialogs');
includeCore('springbok.jqueryui');
window.searchable={
	createTerm:function(val,onAdd,input,url,options){
		S.dialogs.prompt('Création du terme : '+val,'Quel est le type du terme "'+val+'" ?','Valider',
			{
				'20':'Nom Masculin','21':'Nom Féminin','22':'Nom Pluriel','23':'Epicène',
				'30':'Abbréviation','31':'Acronyme',
				'5':'Erreur orthographique'
			},
			function(type){
				$.get(url+'create'+options.url,{name:val,type:type},onAdd);
			}
		)
	}
};