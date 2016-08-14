<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller{
	public function index(){
		$this->assign('conf', json_decode(file_get_contents(__DIR__.'/../../../config/index.conf'), true));
		$this->assign('carImg', json_decode(file_get_contents(__DIR__.'/../../../config/carousel.conf'), true));
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	public function description(){
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	public function query_domi(){
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	private function buildInfo(){
		return '还在开发中哦~~<a href="'.config('site_root').'/">点击</a>回到首页';
	}
}
