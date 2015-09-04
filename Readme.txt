【目录说明】

1. Application - 应用目录
2. CdvPHP - 框架
3. Config - 框架配置 / 开发者自定义配置
4. Log - 站点日志记录文件，Log文件会自动创建
5. Public - 入口页了(首页就不区分大小写了)

【WebServer配置】

    #Nginx配置

    server
    {
        listen 80; 
        index index.php;

        server_name test.cdvphp.cn;
        root /home/fanjiapeng/test/cdvphp/Public;
        access_log /data/nginx/logs/fanjiapeng/access_log;
        error_log /data/nginx/logs/fanjiapeng/error_log;

        location ~* \.php$ 
        {   
            fastcgi_pass 127.0.0.1:9000;
            include fastcgi.conf;
            fastcgi_index index.php;
        }
    }

    #Apache配置; conf/extra/httpd-vhosts.conf

    <VirtualHost *:80>
        ServerAdmin admin@cdvphp.cn
        DocumentRoot "/home/fanjiapeng/test/cdvphp/Public"
        ServerName test.cdvphp.cn
        ServerAlias test.cdvphp.cn
        ErrorLog "logs/cdv.cn-error.log"
        CustomLog "logs/cdv.cn-access.log" common
    </VirtualHost>

【运行框架】

1. WEB服务器解析到Public目录  -- 就可以运行了，一切都很简单吧！
2. Log - 具备读写权限
3. Application\View\TemplatesCache 具备读写权限
4. 如果把Public\index.php放到上一级目录，修改地方如下:

	define('FRAMEWORK_PATH', __DIR__ . '/CdvPHP');
	define('ROOT_PATH', __DIR__);

【框架核心思想说明】

四层架构思想，常见组合如下

1. Controller + Logic + Model + View
2. Controller + Model + view
3. Controller + Logic + Model
4. Controller + Logic
5. Controller + View

【框架入门篇 - 具体命名规则看文件就一目了然了】

Application 		目录下全是例子了
Application\Library 开发者自定义类存放目录，命名规则 "类名Library"

1. DB操作例子：User.php
2. 自动获取GET+POST例子: Gpc.php
3. 手动获取GET+POST等例子: Http.php
4. 类加载例子: Class.php
5. 模版例子: ViewTest.php
6. 自定义类使用例子: Library.php
7. 留言本例子：Book.php
8. Redis、Memcached例子：Cache.php
9. 输出Json格式API接口例子：Api.php
10. 框架入门首页：Index.php

【框架 - 已经封装的类库说明; 详细手册预计跟官网一起上线】

1. BasePdo - Mysql PDO + Curd类库
2. Censor - 非法关键字类库
3. Code - 验证码
4. cURL - curl封装类
5. HashTable - 分表分库
6. Http - request（获取全局数组）、reponse（输出）、Api输出类
7. Logger - 日志记录
8. RandLottery - 根据权重比例随机算法类
9. Session - 会话
10. Sign - 字典序算法类库
11. Superglobal - 超级全局变量
12. View - 模版类
13. Timer - 计时器
14. Curl  - cURL封装类
15. Cache - (Memcached类、Redis类)

【框架升级更新】

1. 正式版，直接下载新版替换CdvPHP目录即可
2. 示例版，全部替换即可

【注】

1. CdvPHP的未来需要大家的扶持与迭代，我们一起来参与开发吧！感谢所有对CdvPHP关注的朋友！
2. 在线手册(有大量的例子)：http://www.cdvphp.com/help4/
3. 首页: http://域名/index.php?method=Index.index
4. 留言本、DB操作例子请先导入根目录下的SQL脚本: DB.sql
5. QQ群：26778603

