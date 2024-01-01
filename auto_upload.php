<?php
$tag = 'v2.0.3';
$msg = '进行了一些细微调整';

echo `git add .`;               //添加所有更改
echo `git commit -m "$msg"`;    //提交更改
echo `git tag $tag`;            //创建新版本标签
echo `git push origin master`;  //提交新版本到git上
echo `git push origin --tags`;  //提交新版本标签到git上
//同步更新packagist上的包信息
//记得修改自己的 API_TOKEN 和 PACKAGIST_PACKAGE_URL 哦
echo `curl -XPOST -H "content-type:application/json" "https://packagist.org/api/update-package?username=MillionMile&apiToken=d16d574e396e53f138c7" -d "{\"repository\":{\"url\":\"https://packagist.org/api/github?username=langhanyun\"}}"`;

