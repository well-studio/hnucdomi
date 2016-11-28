<?php
namespace app\vote\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Index extends Controller {
	public function index(){
		// if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		$now = strtotime(date('Y-m-d', time()));
		$res = Db::query('SELECT * FROM `wl_vote` WHERE `start_time` <= ? AND `end_time` >= ? ORDER BY `end_time` ASC', [ $now, $now]);
		foreach($res as &$v){
			if ($v['need_code'] === 1) {
				$v['name'] .= ' <span class="am-badge am-badge-primary">邀</span>';
			}
			$t =  (int)(($v['end_time']-$now)/86400);
			if ($t === 0) {
				$v['remain'] = '不到 1 天结束';
			} else if($t < 4) {
				$v['remain'] = '只剩 ' . $t . ' 天结束';
			} else if ($t <= 15) {
				$v['remain'] = '还有 ' . $t . ' 天结束';
			} else {
				$v['remain'] = '截止至' . date('Y-m-d', $v['end_time']);
			}
		}
		$this->assign('vote', $res);
		return $this->fetch();
	}

	public function detail(){
		// if (!Request::instance()->isMobile()) return '本网页没有电脑版哦~~';
		$uuid = input('param.uuid');
		$this->assign('option', json_decode(file_get_contents(__DIR__.'/../../../config/option.conf'), true));
		$vote = Db::query('SELECT `id`,`name`,`uuid`,`created`,`start_time`,`end_time`,`need_code` FROM `wl_vote` WHERE `uuid` = ? LIMIT 1', [$uuid]);
		if(!isset($vote[0])){
			return '404';
		}
		$vote = $vote[0];
		$vote['created'] = date('Y-m-d', $vote['created']);
		$vote['start_time'] = date('Y-m-d', $vote['start_time']);
		$vote['end_time'] = date('Y-m-d', $vote['end_time']);
		$vote_item_option_temp = Db::query('SELECT `wl_vote_item`.`vid`,`wl_vote_item`.`id` AS `iid`,`wl_vote_item`.`description`,`wl_vote_item`.`limit_count`,`wl_vote_option`.`content`,`wl_vote_option`.`cnt`,`wl_vote_option`.`id` AS `oid`  FROM `wl_vote_item` LEFT JOIN `wl_vote_option` ON `wl_vote_item`.`id` = `wl_vote_option`.`iid` WHERE `wl_vote_item`.`vid` = (SELECT `id` FROM `wl_vote` WHERE `wl_vote`.`uuid` = ?)', [$uuid]);
		$vote_item_option = [];
		foreach ($vote_item_option_temp as $v) {
			$vote_item_option[$v['iid']]['iid'] = $v['iid'];
			$vote_item_option[$v['iid']]['description'] = $v['description'];
			$vote_item_option[$v['iid']]['limit_count'] = $v['limit_count'];
			$tempArr = [
				'oid' => $v['oid'],
				'content' => $v['content'],
				// 'count' => $v['cnt']
			];
			if ($v['limit_count'] === 1) {
				$vote_item_option[$v['iid']]['description'] .= ' <small>（单选）</small>';
			} else {
				$vote_item_option[$v['iid']]['description'] .= ' <small>（限选 ' . $v['limit_count'] . ' 个）</small>';
			}
			$vote_item_option[$v['iid']]['option'][] = $tempArr;
		}
		$ret = ['vote' => $vote, 'item' => array_values($vote_item_option)];
		$this->assign('detail', $ret);
		$this->assign('detail_json', json_encode($ret));
		return $this->fetch();
	}
	public function check_inv(){
		if(!isset($_SERVER['HTTP_REFERER'])) return '404';
		if(preg_match('/\/vote\/(\w+?)(?:\?|$|#|\/)/', $_SERVER['HTTP_REFERER'], $res)){
			$inv = input('post.inv');
			$uuid = $res[1];
			$res = Db::query('SELECT * FROM `wl_vote_invitation` WHERE `vid` = (SELECT `id` FROM `wl_vote` WHERE `uuid` = ? LIMIT 1) AND `code` = ? AND `used` = 0', [$uuid, $inv]);
			if(isset($res[0])){
				return ['err'=>0, 'msg' => 'success'];
			}else{
				return ['err'=>233, 'msg'=>'邀请码错误，请重新输入'];
			}
		}else{
			return ['err'=>233, 'msg'=>'referrer非法'];
		}
	}
	public function submit(){
		if(preg_match('/\/vote\/(\w+?)(?:\?|$|#|\/)/', $_SERVER['HTTP_REFERER'], $res)){
			$inv = input('post.detail.inv');
			$uuid = $res[1];
			//需要邀请码
			// $res = Db::query('SELECT * FROM `wl_vote_invitation` WHERE `vid` = (SELECT `id` FROM `wl_vote` WHERE `uuid` = ? LIMIT 1) AND `code` = ?', [$uuid, $inv]);
			$res = Db::query('SELECT * FROM `wl_vote` WHERE `uuid` = ?', [$uuid]);
			$flag = true;
			if(isset($res[0]) && $res[0]['need_code']===1){
				$res = Db::query('SELECT * FROM `wl_vote_invitation` WHERE `vid` = ? AND `code` = ? AND `used` = 0', [$res[0]['id'], $inv]);
				$flag = isset($res[0]) ? true: false;
			}else if(!isset($res[0])){
				return ['err'=>233, 'msg'=>'referrer非法'];
			}
			if($flag){// 邀请码验证成功了

				$vote = Db::query('SELECT `id`,`name`,`uuid`,`created`,`start_time`,`end_time`,`need_code` FROM `wl_vote` WHERE `uuid` = ? LIMIT 1', [$uuid]);
				if(!isset($vote[0])){
					return '404';
				}
				$vote = $vote[0];
				$vote['created'] = date('Y-m-d', $vote['created']);
				$vote['start_time'] = date('Y-m-d', $vote['start_time']);
				$vote['end_time'] = date('Y-m-d', $vote['end_time']);
				$vote_item_option_temp = Db::query('SELECT `wl_vote_item`.`vid`,`wl_vote_item`.`id` AS `iid`,`wl_vote_item`.`description`,`wl_vote_item`.`limit_count`,`wl_vote_option`.`content`,`wl_vote_option`.`cnt`,`wl_vote_option`.`id` AS `oid`  FROM `wl_vote_item` LEFT JOIN `wl_vote_option` ON `wl_vote_item`.`id` = `wl_vote_option`.`iid` WHERE `wl_vote_item`.`vid` = (SELECT `id` FROM `wl_vote` WHERE `wl_vote`.`uuid` = ?)', [$uuid]);
				$vote_item_option = [];
				foreach ($vote_item_option_temp as $v) {
					$vote_item_option[$v['iid']]['iid'] = $v['iid'];
					$vote_item_option[$v['iid']]['description'] = $v['description'];
					$vote_item_option[$v['iid']]['limit_count'] = $v['limit_count'];
					$tempArr = [
						'oid' => $v['oid'],
						'content' => $v['content'],
						// 'count' => $v['cnt']
					];
					$vote_item_option[$v['iid']]['option'][] = $tempArr;
				}
				$ret = ['vote' => $vote, 'item' => array_values($vote_item_option)];
				$sub = input('post.');


				$itemLength = 0;
				$itemIsUsed = 0;
				/* 验证票是否有多投和所有票是否都投了 start  */
				foreach($ret['item'] as $item){
					foreach($item['option'] as $option){
						foreach($sub['detail']['opt'] as $subOptOid => $subOptOidVal){
							if($subOptOidVal === 'true' && $subOptOid === $option['oid']){
								$item['limit_count']--;
								break ;
							}
						}
					}
					if($item['limit_count']<0){ //多投了
						//前端正常不会得到这个，防止恶意ajax
						return ['err'=>233, '请求参数错误'];
					}
					$itemLength++;
					foreach($item['option'] as $option){
						foreach($sub['detail']['opt'] as $subOptOid => $subOptOidVal){
							if($subOptOidVal === 'true' && $subOptOid === $option['oid']){
								$itemIsUsed++;
								break 2;
							}
						}
					}
				}
				if($itemLength !== $itemIsUsed){ //有题目未投
					//前端正常不会得到这个，防止恶意ajax
					return ['err'=>233, '请求参数错误'];
				}
				/* 验证票是否有多投和所有票是否都投了 end  */

				/* 验证全部通过，设邀请码无效，设选项票数+1 */
				Db::execute('UPDATE `wl_vote_invitation` SET `used` = ? WHERE `code` = ?', [time(), $inv]);
				// var_dump($ret['item'][0]);
				foreach($sub['detail']['opt'] as $k => $v){
					if($v === 'true'){
						Db::execute('UPDATE `wl_vote_option` SET `cnt` = `cnt` + 1 WHERE `id` = ?', [$k]);
					}
				}
				return ['err'=>0, 'msg'=>'投票成功'];
			}else{
				//前端正常不会得到这个，防止恶意ajax
				return ['err'=>233, 'msg'=>'邀请码错误哦'];
			}
		}else{
			return ['err'=>233, 'msg'=>'referrer非法'];
		}
	}
}