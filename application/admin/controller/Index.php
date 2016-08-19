<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;

class Index extends Controller {
	public function index(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}

		//首页按钮配置文件
		$this->assign('conf', json_decode(file_get_contents(__DIR__.'/../../../config/index.conf'), true));

		//首页配置项
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));

		//scan图片，以及路径
		$hd = opendir(__DIR__.'/../../../public/images');
		$imgArr = [];
		while(false !== ($file = readdir($hd))){
			if ($file != "." && $file != "..") {
				$imgArr[] = $file;
			}
		}
		closedir($hd);
		$this->assign('imgNames', $imgArr);

		//首页轮播图地址
		$this->assign('carImgStr', implode(json_decode(file_get_contents(__DIR__.'/../../../config/carousel.conf'), true), "\n"));

		//获取数据库数据条数
		$c = Db::query('SELECT count(*) as `count` FROM `stu_info`');
		$this->assign('stu_num', $c[0]['count']);
		return $this->fetch();;
	}

	public function changeIndex(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}

		$pst = input('post.');
		$arr = [];
		for($i=0; $i<9; $i++){
			$arr[] = [
				'title' => $pst['title'][$i],
				'icon' => $pst['icon'][$i],
				'href' => $pst['href'][$i],
			];
		}
		if(file_put_contents(__DIR__.'/../../../config/index.conf', json_encode($arr))){
			return $this->success('修改成功', config('site_root').'/admin');
		}else{
			return $this->error('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin');
		}
	}

	public function fileUpload() {
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}

		if(preg_match('/^.+(\.xls|\.xlsx)$/', $_FILES['file']['name'], $res)){
			vendor('Excel.PHPExcel.IOFactory');
			$reader = null;
			if($res[1] === '.xls'){
				$reader = \PHPExcel_IOFactory::createReader('Excel5');
			}else{
				$reader = \PHPExcel_IOFactory::createReader('Excel2007');
			}
			$ex = $reader->load($_FILES['file']['tmp_name']);
			$st = $ex->getSheet(0);


			$highestRow = $st->getHighestRow(); // 取得总行数
			$highestColumm = $st->getHighestColumn(); // 取得总列数
			$highestColumm= \PHPExcel_Cell::columnIndexFromString($highestColumm);
			if($highestColumm !== 6){
				var_dump($highestColumm);
				return $this->error('您上传的数据好像有点问题呐~~~', config('site_root').'/admin');
			}

			$k = [];
			for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
				$r = [];
				for ($column = 0; $column < 6; $column++) {//列数是以第0列开始
					$r[] = $st->getCellByColumnAndRow($column, $row)->getValue();
				}
				$k[] = $r;
			}
			foreach ($k as $v) {
				Db::execute('INSERT INTO `stu_info`(`exm_id`,`id`, `name`, `academy`, `class`, `domi_num`) VALUES(?,?,?,?,?,?) ON duplicate key UPDATE `exm_id` = values(`exm_id`), `name` = VALUES(`name`), `academy` = VALUES(`academy`), `class` = VALUES(`class`), `domi_num` = `domi_num`+VALUES(`domi_num`)', [$v[0], $v[1], $v[2], $v[3], $v[4], $v[5]]);
			}
		}
		return $this->success('上传成功', config('site_root').'/admin');
	}

	public function imageUpload(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}

		if(file_put_contents(__DIR__.'/../../../public/images/'.$_FILES['imageFile']['name'], file_get_contents($_FILES['imageFile']['tmp_name']))){
			return $this->success('上传成功', config('site_root').'/admin');
		}else{
			return $this->success('未知错误，可能是配置文件不可写，请联系管理员', config('site_root').'/admin');
		}
	}
}
