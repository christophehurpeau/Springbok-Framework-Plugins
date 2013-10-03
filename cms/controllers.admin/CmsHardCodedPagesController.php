<?php
/** @Check('ACSecureAdmin') @Acl('CMS') */
class CmsHardCodedPagesController extends Controller{
	
	/** */
	static function index(){
		CmsHardCodedPage::Table()->fields('id,status')->withParent('name,created,updated')
			->where(array('status !='=>CmsHardCodedPage::DELETED))->orderBy(array('sb.created'=>'DESC'))
			->allowFilters()
			->paginate()->fields(array('id','name','status','created','updated'))->actionClick('edit')
			->render(_t('plugin.cms.HardCodedPages'),array('fields'=>array('name'=>_tF('CmsHardCodedPage','New').' :','link'=>_tF('CmsHardCodedPage','link'))));
	}
	
	/** @ValidParams @Required('cmsHardCodedPage')
	* cmsHardCodedPage > @Valid('name','link')
	*/ function add(CmsHardCodedPage $cmsHardCodedPage){
		$cmsHardCodedPage->status=CmsHardCodedPage::VALID;
		$cmsHardCodedPage->visible=true;
		$cmsHardCodedPage->insert();
		redirect('/cmsHardCodedPages');
	}
	
	/** @ValidParams @Required('id') */
	static function edit(int $id){
		$page=CmsHardCodedPage::ById($id)->mustFetch();
		mset($page,$id);
		render();
	}
}