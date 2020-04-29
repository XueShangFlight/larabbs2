<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    //全局中间件
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,//修正代理服务器后的服务器参数
        \App\Http\Middleware\CheckForMaintenanceMode::class,//检测应用是否进入【维护模式】
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,//检测表单请求的数据是否过大
        \App\Http\Middleware\TrimStrings::class,//对提交的请求参数进行PHP函数‘trim()’处理
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,//将提交请求参数中空子串转换为null
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        //web中间件组，应用于routes/web.php路由文件，在RouteServiceProvider中设定
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,//Cookie加密解密
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,//将Cookie添加到响应中
            \Illuminate\Session\Middleware\StartSession::class,//开启会话
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,//将系统的错误数据注入到视图变量$errors中
            \App\Http\Middleware\VerifyCsrfToken::class,//检测CSRF，防止跨站请求伪造的安全威胁
            \Illuminate\Routing\Middleware\SubstituteBindings::class,//处理路由绑定
            \App\Http\Middleware\EnsureEmailIsVerified::class,//强制用户邮箱认证
            \App\Http\Middleware\RecordLastActivedTime::class,//记录用户最后活跃时间
        ],

        //API中间件组，应用于route/api.php路由文件，在RouteServiceProvider中设定
        'api' => [
            'throttle:60,1',
            'bindings',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,// 使用别名来调用中间件
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    //中间件别名设置，允许你使用别名调用中间件，例如上面的api中间件组调用
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,//只有登录用户才能访问，我们在控制器的构造方法中大量使用
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,//HTTP Basic Auth 认证
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,//处理路由绑定
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,//用户授权功能
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,//只有游客才能访问，在register和login请求中使用，只有未登录用户才能访问这些页面
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,//签名认证，找回密码章节提过
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,//访问节流，类似于【1分钟只能请求10次】的需求，一般在API中使用
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,//Laravel自带的强制用户邮箱认证的中间件，为了更加贴近我们的逻辑，已被重写
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    //设定中间件优先级，此数组定义了除【全局中间件】以外的中间件执行顺序
    //可以看到StartSession永远是最开始执行的，因为StartSession后，我们才能在程序中使用Auth等用户认证的功能
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // 一小时执行一次『活跃用户』数据生成的命令
        $schedule->command('larabbs:calculate-active-user')->hourly();

        //每日零时执行一次
        $schedule->command('larabbs:sync-user-actived-at')->dailyAt('00:00');
    }
}
