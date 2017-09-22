# 基于laravel框架封装的一个分块导出组件
> 项目中涉及到大数据量的导出的时候，使用传统的页面导出方式很容易导出内存溢出，通常情况下采用分页导出的方式，循环去读取数据，输出到缓存区


## 环境要求

1.Laravel 5.1以上

2.PHP5.6以上


## 安装package

这是一个标准的 Composer 的包,你可以直接通过下面的命令行来安装:

```
composer require stephen/chunk-export
```
或者在你的 composer.json 文件中添加:

"stephen/chunk-export" : "~1.0.0"
然后执行 composer install

## 配置

### 註冊Service Provider

在config/app.php中添加provider

```
  'providers' => [
        \Stephen\Chunk\ChunkServiceProvier::class,

    ],
```


## 在laravel中使用案例

```
 <?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Stephen\Chunk\Service\ChunkService;

class Export extends Controller
{
    //
    /**
     * @var ChunkService
     */
    private $chunkService;
    /**
     * @var User
     */
    private $user;

    /**
     * Export constructor.
     * @param ChunkService $chunkService
     */
    public function __construct(ChunkService $chunkService, User $user)
    {
        $this->chunkService = $chunkService;
        $this->user = $user;
    }

    public function index()
    {
        $config['models'] = $this->user->select('id', 'name', 'email')->orderBy('id', 'DESC');
        $config['headers'] = ['姓名', '邮箱'];
        $config['filename'] = '用户信息.csv';
        $pageSize = 10;

        $callback = function ($user) {
            $output = [];

            $output['name'] = iconv('UTF-8', 'GBK', $user['name']);
            $output['email'] = iconv('UTF-8', 'GBK', $user['email']);
            return $output;

        };
        $this->chunkService->exportCsv($config, $callback, $pageSize);


    }
}


```







