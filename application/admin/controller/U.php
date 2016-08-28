<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;

class U extends Controller {
	public function index(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
	}

	public function login(){
		Session::set('admin', null);
		return $this->fetch();
	}

	public function ajax(){
		if(!Input('post.func')) return ['err'=>'233', 'msg'=> '干啥哟'];
		switch(Input('post.func')){
			case 'login': return $this->ajaxLogin(); break;
			case 'delDataTable': return $this->ajaxDelDataTable(); break;
			case 'delImg' :return $this->ajaxDelImg(); break;
			case 'queryClass' : return $this->ajaxQueryClass(); break;
		}
	}

	public function changePass(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		if(input('post.changePass') !== '123456'){
			return $this->error('参数非法');
		}else if(input('post.newpass') !== input('post.conpass')){
			return $this->error('确认密码错误');
		}else{
			$pass = require(__DIR__.'/../../../config/password.php');
			if(md5(input('post.oldpass').'hnucdomi') !== $pass){
				return $this->error('旧密码错误');
			}
			$str = '<?php'."\n".'return \''.md5(input('newpass').'hnucdomi').'\';'."\n";
			if(file_put_contents(__DIR__.'/../../../config/password.php', $str)){
				return $this->success('密码修改成功', config('site_root').'/admin/password_change');
			}else{
				return $this->error('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin/password_change');
			}
		}
	}

	public function changeCarousel(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		if(file_put_contents(__DIR__.'/../../../config/carousel.conf', json_encode(explode("\n", str_replace("\r\n", "\n", trim(input('post.')['carouselUrls'])))))){
			return $this->success('修改成功', config('site_root').'/admin/index_change');
		}else{
			return $this->error('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin/index_change');
		}
	}

	public function changeImageVideoList(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		$pst = input('post.');
		$arr = [];
		for($i=0, $len=count($pst['image']); $i<$len; $i++){
			if(
				empty($pst['image'][$i])||
				empty($pst['type'][$i])||
				empty($pst['title'][$i])||
				empty($pst['description'][$i])||
				empty($pst['href'][$i])
			) continue;
			$arr[] = [
				'image'       => $pst['image'][$i],
				'type'        => $pst['type'][$i],
				'title'       => $pst['title'][$i],
				'description' => $pst['description'][$i],
				'href'        => $pst['href'][$i],
			];
		}
		if(file_put_contents(__DIR__.'/../../../config/img_video.conf', json_encode($arr))){
			return $this->success('修改成功', config('site_root').'/admin/list_change');
		}else{
			return $this->error('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin/list_change');
		}
	}

	public function changeOptions(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		if(file_put_contents(__DIR__.'/../../../config/option.conf', json_encode(input('post.')))){
			return $this->success('修改成功', config('site_root').'/admin/index_change');
		}else{
			return $this->error('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin/index_change');
		}
	}

	private function ajaxLogin(){
		if($this->checkPassword()){
			Session::set('admin', 'zzliux');
			return ['err'=>0, 'msg'=>'登录成功'];
		}else{
			return ['err'=>1, 'msg'=>'密码错误'];
		}
	}

	private function ajaxDelDataTable(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		Db::execute('DELETE FROM `stu_info`');
		return ['err'=>0, 'msg'=>'删除成功'];
	}

	private function ajaxDelImg(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		unlink(__DIR__.'/../../../public/images/'.input('name'));
		return ['err'=>0, 'msg'=>'删除成功'];
	}

	private function checkPassword(){
		return md5(input('post.pass').'hnucdomi') === require(__DIR__.'/../../../config/password.php');
	}

	private function ajaxQueryClass(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/U/login');
		}
		if(!input('post.name')){
			return ['err'=>1, 'msg'=>'error request'];
		}
		$c = Db::query('SELECT * FROM `stu_info` WHERE `class` = ?', [input('post.name')]);
		return $c;
	}
}
