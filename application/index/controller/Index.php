<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller{
	public function index(){
		if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';

		$this->assign('conf', json_decode(file_get_contents(__DIR__.'/../../../config/index.conf'), true));
		$this->assign('carImg', json_decode(file_get_contents(__DIR__.'/../../../config/carousel.conf'), true));
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	public function query_stu_info(){
		if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';

		Session::set('anti_spider', '(｀･ω･´)');
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	public function query_domi(){
		if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';

		Session::set('anti_spider', '(｀･ω･´)');
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	public function military_theory(){
		if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';

		Session::set('anti_spider', '(｀･ω･´)');
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		return $this->fetch();
	}

	//list的函数名竟然不能用。。
	public function list_(){
		if (!Request::instance()->isMobile() && input('get.viewTemp') !== '1') return '本网页没有电脑版哦~~';

		Session::set('anti_spider', '(｀･ω･´)');
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		$this->assign('items', json_decode(file_get_contents(__DIR__.'/../../../config/img_video.conf'), true));
		return $this->fetch('list');
	}

	private function buildInfo(){
		return '还在开发中哦~~<a href="'.config('site_root').'/">点击</a>回到首页';
	}
}
