_.seo={
	init:function(inputName,ulKeywords){
		if(inputName){
			var inputNameUpdate=function(){
				var val=$(this).val();
				$('#SeoSlugAuto').val(val.sbSlug()).change();
				$('#SeoMeta_titleAuto').val(val).change();
			};
			inputName.change(inputNameUpdate).delayedKeyup(inputNameUpdate);
		}
		if(ulKeywords){
			ulKeywords.change(function(){
				$('#SeoMeta_keywordsAuto').val($(this).find('li span').map(function(){return $(this).text()}).get().sort().join(', '));
			});
		}
		['Slug','Meta_title','Meta_descr','Meta_keywords'].sEach(function(i,m){
			var input=$('#Seo'+m), val=input.val(), tr=input.closest('tr'),
				mw=tr.find('.manuel .words'), mc=tr.find('.manuel .chars'),
				updateWords=function(w,c,val){
					w.text(val.sbWordsCount());
					c.text(val.length);
				},
				eventUpdateWords=function(){updateWords(mw,mc,$(this).val())};
				
			input.change(eventUpdateWords).delayedKeyup(eventUpdateWords);
			updateWords(mw,mc,val);
			
			input=$('#Seo'+m+'Auto'); val=input.val(); tr=input.closest('tr');
			var aw=tr.find('.auto .words'), ac=tr.find('.auto .chars');
			eventUpdateWords=function(){updateWords(aw,ac,$(this).val())};
			
			input.change(eventUpdateWords);
			updateWords(aw,ac,val);
		});
	},
	tinymceChanged_metaKeywords:function(inst){
		$("#SeoMeta_descrAuto").val(inst.getBody().innerHTML.sbStripTags().replace(/[\s\r\n]+/g,' ').trim()).change();
	},
	meta:function(t){
		var tr=$(t).closest('tr');
		if(tr.hasClass('auto')){
			tr.removeClass('auto').addClass('manuel');
			var input=tr.find(':input.manuel').prop('disabled',false);
			if(input.hasClass('autoOnLoad'))
				input.removeClass('autoOnLoad').val(tr.find(':input.auto').val()).change();
			tr.find('a').text('Manuel');
		}else{
			tr.removeClass('manuel').addClass('auto');
			var input=tr.find(':input.manuel').prop('disabled',true);
			if(input.val()===tr.find(':input.auto').val()) input.addClass('autoOnLoad');
			tr.find('a').text('Automatique');
		}
		return false;
	},
};
