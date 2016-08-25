<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Ajax extends Controller{
	public function index(){
		if(!preg_match('/^http:\/\/'.config('domain').'/', $_SERVER["HTTP_REFERER"]) ||
			Session::get('anti_spider') !== '(｀･ω･´)' ||
			!Request::instance()->isMobile()) {
			return 'error request';
		}
		if(input('post.func') === 'query_domi'){
			return $this->query_domi();
		}else if(input('post.func') === 'query_stu_info'){
			return $this->query_stu_info();
		}
		return 'error request';
	}

	private function query_domi() {
		$out = Db::query('SELECT `id` as `stu_id`,`name` as `stu_name`, `academy` as `stu_academy`, `class` as `stu_class`, `domi_num` as `stu_domiNum` FROM `stu_info` WHERE `name` = ? OR `id` = ?', [input('post.stu_info'), intval(input('post.stu_info'))]);
		for($i=0,$cnt=count($out); $i<$cnt; $i++) {
			$t = Db::query('SELECT `name` as `stu_name` FROM `stu_info` WHERE `domi_num` = ?', [$out[$i]['stu_domiNum']]);
			$out[$i]['domi_mate'] = [];
			for($j=0, $cnt2=count($t); $j<$cnt2; $j++) {
				if($t[$j]['stu_name'] !== $out[$i]['stu_name'])
					$out[$i]['domi_mate'][] = $t[$j]['stu_name'];
			}
		}
		return $out;
	}

	private function query_stu_info(){
		$out = Db::query('SELECT `id` as `stu_id`,`name` as `stu_name`, `academy` as `stu_academy`, `major` as `stu_major`, `class` as `stu_class`, `note` as `stu_note` FROM `stu_info` WHERE `exm_id` = ? LIMIT 1', [input('post.stu_info')]);
		return $out;
	}
}
