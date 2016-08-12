### 安装
* 将config/stu_info.sql导入进mysql
* 在application/config.php配置相关信息
* 将整个目录设置为777权限(chmod -R 0777 hnucdomi)

### admin
初始密码为空，进入后台后可修改密码

##### 宿舍查询的数据的导入
使用xls/xlsx格式的excel文档，表格文档不允许超过6列，  
第一行的6列的表头**不允许修改**，修改后果自负，  
单个文件不允许超过php的上传限制，一般为2M，  

##### 首页可定制内容
- 站点标题
- 版权信息
- 首页按钮(9个)
	- 按钮标题
	- 按钮图标(使用[fontawesome](http://fontawesome.io/icons/)图标)
	- 点击行为(href)
		- 链接跳转
		- javascript: layer.(func)		//(layer弹出层)
		- javacsript: alertImg(url)		//图片弹出层
- 首页轮播图片

##### 图片
- 图片上传
- 图片删除

##### 修改密码