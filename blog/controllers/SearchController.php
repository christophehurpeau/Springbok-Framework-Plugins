<?php
class SearchController extends Controller{
	/** @ValidParams @NotEmpty('term') */
	static function index($term){
		$_GET['term']=$term;
		try{
			new CSearchResult(new ACPostSearch(),$term);
			render('results');
			exit;
		}catch(SPaginationOverrunException $ex){
			self::header404();
			renderText('0');
			exit;
		}catch(SearchException $ex){
			set('message',$ex->getMessage());
			self::header404();
			renderText('0');
			exit;
		}
	}
}