# 基于laravel框架封装的一个分块导出组件
> 项目中涉及到大数据量的导出的时候，使用传统的页面导出方式很容易导出内存溢出，通常情况下采用分页导出的方式，循环去读取数据，输出到缓存区


## 环境要求

1.Laravel 5.5

2.PHP7.0以上


## 安装package

```
composer require stephen/chunk-export
```

## 配置

### 註冊Service Provider

在config/app.php中註冊ChunkServiceProvier

```
  'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        \Stephen\Chunk\ChunkServiceProvier::class,

    ],
```

最后一行
```

oomusou\helloworld\HelloWorldServiceProvider::class,

```

## 使用

新建一个控制器
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
        $pageSize = 10;
        $config['models'] = $this->user->select('id', 'name', 'email')->orderBy('id', 'DESC');
        $config['headers'] = ['姓名', '邮箱'];
        $config['filename'] = '用户信息.csv';

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







