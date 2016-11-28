<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;

class Vote extends Controller {
	public function index(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		return '';
	}

	public function create(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		DB::execute('INSERT INTO `wl_vote`(`name`, `uuid`, `created`, `start_time`, `end_time`) VALUES(?,?,?,?,?)', [
			input('post.cre_name'),
			substr(md5(time().microtime(true).rand()), 8, 16),
			time(),
			strtotime(input('post.cre_start_time')),
			strtotime(input('post.cre_end_time')),
			]);
		return $this->success('新建投票成功');
	}
	public function delete(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		$id = (int)(input('post.id'));
		Db::execute('DELETE FROM `wl_vote_invitation` WHERE `vid` = ?', [$id]);
		Db::execute('DELETE FROM `wl_vote_option` WHERE `iid` IN (SELECT `id` FROM `wl_vote_item` WHERE `vid` = ?)', [$id]);
		Db::execute('DELETE FROM `wl_vote_item` WHERE `vid` = ?', [$id]);
		Db::execute('DELETE FROM `wl_vote` WHERE `id` = ?', [$id]);
		return ['err' => 0, 'msg' => '删除成功'];
	}

	public function detail(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		$vote = Db::query('SELECT `id`,`name`,`uuid`,`created`,`start_time`,`end_time`,`need_code` FROM `wl_vote` WHERE `id` = ?', [(int)(input('post.id'))]);
		$vote = $vote[0];
		$vote['created'] = date('Y-m-d', $vote['created']);
		$vote['start_time'] = date('Y-m-d', $vote['start_time']);
		$vote['end_time'] = date('Y-m-d', $vote['end_time']);
		$vote_item_option_temp = Db::query('SELECT `wl_vote_item`.`vid`,`wl_vote_item`.`id` AS `iid`,`wl_vote_item`.`description`,`wl_vote_item`.`limit_count`,`wl_vote_option`.`content`,`wl_vote_option`.`cnt`,`wl_vote_option`.`id` AS `oid`  FROM `wl_vote_item` LEFT JOIN `wl_vote_option` ON `wl_vote_item`.`id` = `wl_vote_option`.`iid` WHERE `wl_vote_item`.`vid` = ?', [(int)(input('post.id'))]);
		$vote_item_option = [];
		foreach ($vote_item_option_temp as $v) {
			$vote_item_option[$v['iid']]['iid'] = $v['iid'];
			$vote_item_option[$v['iid']]['description'] = $v['description'];
			$vote_item_option[$v['iid']]['limit_count'] = $v['limit_count'];
			$tempArr = [
				'oid' => $v['oid'],
				'content' => $v['content'],
				'count' => $v['cnt']
			];
/*			if ($v['limit_count'] == 1) {
				$vote_item_option[$v['iid']]['description'] .= ' <small>（单选）</small>';
			} else {
				$vote_item_option[$v['iid']]['description'] .= ' <small>（限选 ' . $v['limit_count'] . ' 个）</small>';
			}*/
			$vote_item_option[$v['iid']]['option'][] = $tempArr;
		}
		return ['vote' => $vote, 'item' => array_values($vote_item_option)];
	}

	public function detail_invitations(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		$vid = input('post.vid');
		$res = Db::query('SELECT * FROM `wl_vote_invitation` WHERE `vid` = ?', [$vid]);
		// var_dump($res);
		return $res;
	}

	public function change(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		// var_dump(input('post.'));
		// exit();
		Db::execute('UPDATE `wl_vote` SET `name` = ? , `start_time` = ? , `end_time` = ? , `need_code` = ? WHERE `id` = ?',[
			input('post.cha_name'),
			strtotime(input('post.cha_start_time')),
			strtotime(input('post.cha_end_time')),
			(int)(input('post.cha_need_code')),
			(int)(input('post.vote_id'))
		]);// 更新投票信息
		foreach(input('post.') as $k => $v){
			if(preg_match('/^vote_item_limit_count_(\d+?)$/', $k, $res)){
				$limit_count = $v;
			}else if(preg_match('/^vote_item_(\d+?)$/', $k, $res)){// 更新题目
				Db::execute('UPDATE `wl_vote_item` SET `description` = ?, `limit_count` = ? WHERE `id` = ?', [$v, $limit_count, (int)$res[1]]);
			}else if(preg_match('/^vote_item_(\d+?)_option_(\d+?)$/', $k, $res)){
				// var_dump($res);
				if($res[2] !== '0'){// 更新选项
					$v = trim($v);
					if(!empty($v)){
						Db::execute('UPDATE `wl_vote_option` SET `content` = ? WHERE `id` = ?', [$v, (int)$res[2]]);
					}else{
						Db::execute('DELETE FROM `wl_vote_option` WHERE `id` = ?', [(int)$res[2]]);
					}
				}else{// 对于已存在的题目存入选项
					foreach($v as $vv){
						$vv = trim($vv);
						if(!empty($vv)){
							Db::execute('INSERT INTO `wl_vote_option`(`iid`, `content`) VALUES(?, ?)', [(int)$res[1], $vv]);
						}
					}
				}
			}else if(preg_match('/vote_item_limit_count_(\d+?)_(\d+?)/', $k, $res)){
				$limit_count = $v;
			}else if(preg_match('/^vote_item_(\d+?)_(\d+?)$/', $k, $res)){//插入新题
				$v = trim($v);
				if(!empty($v)){
					Db::execute('INSERT INTO `wl_vote_item`(`vid`,`description`,`limit_count`) VALUES(?, ?, ?)', [(int)input('post.vote_id'), $v, $limit_count]);
				}
				$mapTmpIDRealID[$res[2]] = Db::query('SELECT `id` FROM `wl_vote_item` ORDER BY `id` DESC LIMIT 1')[0]['id'];
			}else if(preg_match('/^vote_item_(\d+?)_option_(\d+?)_(\d+?)$/', $k, $res)){//插入新题的新选项
				if($res[2] === '0'){
					foreach($v as $vv){
						$vv = trim($vv);
						if(!empty($vv)){
							Db::execute('INSERT INTO `wl_vote_option`(`iid`, `content`) VALUES(?, ?)', [$mapTmpIDRealID[$res[1]], $vv]);
						}
					}
				}
			}
		}
		return $this->success('修改成功');
	}
	function invitations_submit(){
		if(Session::get('admin') !== 'zzliux'){
			$this->redirect('admin/u/login');
		}
		$arr = preg_split('/\r?\n/', input('post.')['invitations']);
		$successNum = 0;
		$failNum = 0;
		foreach($arr as $v){
			if(isset($v{0}) && $v{0}!=='#'){
				$v = preg_split('/\t+/', $v);
				$successNum++;
				try{
					Db::execute('INSERT INTO `wl_vote_invitation`(`code`,`vid`,`note`,`created`) VALUES(?,?,?,?)', [$v[0], $v[1], $v[2], time()]);
				}catch(\Exception $e){
					// var_dump($e->getMessage());
					$successNum--;
					$failNum++;
				}
			}
		}
		return $this->success('导入完成，成功'.$successNum.'条，失败'.$failNum.'条');
	}
}
?>