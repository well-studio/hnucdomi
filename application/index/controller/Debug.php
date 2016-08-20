<?php
namespace app\index\controller;
use think\Controller;

class Debug extends Controller{
	public function index(){
		echo '<pre>';
		var_dump($_SERVER);
		echo '</pre>';
	}
}
