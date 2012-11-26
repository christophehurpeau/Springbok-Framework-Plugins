window.tinyMCEPreInit = {
	base:webUrl+'tinymce', 
	suffix:'', 
	query:''
};
includeLib('tinymce/tiny_mce');

includeLib('tinymce/themes/advanced/editor_template');

includeLib('tinymce/plugins/pagebreak/editor_plugin');
includeLib('tinymce/plugins/style/editor_plugin');
includeLib('tinymce/plugins/table/editor_plugin');
includeLib('tinymce/plugins/advimage/editor_plugin');
includeLib('tinymce/plugins/inlinepopups/editor_plugin');
includeLib('tinymce/plugins/contextmenu/editor_plugin');
includeLib('tinymce/plugins/paste/editor_plugin');
includeLib('tinymce/plugins/fullscreen/editor_plugin');
includeLib('tinymce/plugins/wordcount/editor_plugin');
includeLib('tinymce/plugins/autosave/editor_plugin');
includeLib('tinymce/plugins/autolink/editor_plugin');

includeLib('tinymce/plugins/springbok/editor_plugin');
includeLib('tinymce/plugins/springbokclean/editor_plugin');
includeLib('tinymce/plugins/springboklink/editor_plugin');
includeLib('tinymce/plugins/springbokgallery/editor_plugin');