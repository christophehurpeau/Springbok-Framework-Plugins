includePlugin('seo/_seo');
includeCore('ui/dialogs');
includeCore('ui/THtml');
includeCore('springbok.jqueryui');

window.searchable={
	proximities:{0:'0: Identique',1:'1: Synonyme',2:'2: Lien direct',3:'3: Lien indirect',4:'4: Très proche',5:'5: Proche',7:'7: Eloigné',9:'9: Lointain'},
	form:function(type,proximity){
		var createSelect=function(params,selected){ return UObj.implode(params,function(k,v){
						return '<option value="'+k+'"'+(k==selected?' selected="selected"':'')+'>'+S.escape(v)+'</option>'; }) };
		return 'Type : <select class="type wp100">'
				+createSelect({
					'20':'Terme Masculin','21':'Terme Féminin','22':'Terme Pluriel','23':'Epicène','25':'Adjectif',
					'30':'Abbréviation','31':'Acronyme',
					'5':'Erreur orthographique'
				},type)
			+'</select>'
			+'Proximité : <select class="proximity wp100">'
			+createSelect(searchable.proximities,proximity)
			+'</select>'; 
	},
	
	
	createTerm:function(val,onAdd,input,url,options){
		S.dialogs.form('Création du terme : '+val,'<div>Quel est le type du terme "'+val+'" ?</div>'
			+searchable.form()
			,
			'Valider',
			function(div){
				$.get(url+'create'+options.url,{name:val,type:div.find('select.type').val(),proximity:div.find('select.proximity').val()},onAdd);
			}
		);
	},
	addTerm:function(val,onAdd,input,url,options){
		S.dialogs.prompt('Ajout du terme : '+val,'Quelle est la proximité du terme "'+val+'" ?','Valider',searchable.proximities,
				function(proximity){
					val=val.split('-');
					$.get(url+'add'+options.url,{termId:val[0],type:val[1],proximity:proximity},onAdd);
				});
	},
	editTerm:function(li,termId,url,options,actions,ul){
		var formContent=searchable.form(li.find('.typeTerm').attr('rel'),li.find('.proximity').text()),
			spanKwd=li.find('span.keyword[rel]');
		if(spanKwd.length)
			formContent+='<input type="checkbox" class="isKeyword"'+(spanKwd.attr('rel')==='true'?' checked="checked"':'')+'> Utiliser comme mot-clé';
		
		S.dialogs.form('Edition du terme : '+li.text(),formContent,'Valider',
			function(div){
				var data={termId:termId,type:div.find('select.type').val(),proximity:div.find('select.proximity').val()};
				if(spanKwd.length) data.isKeyword=div.find('input.isKeyword').is(':checked') ? '1' : '0';
				$.get(url+'edit'+options.url,data,function(data){
					li.animate({opacity:0.1,height:'toggle'},function(){ li.html(data && data.ok ? data.html+actions : 'ERREUR').animate({opacity:1,height:'toggle'}) });
				});
			}
		);
	}
};