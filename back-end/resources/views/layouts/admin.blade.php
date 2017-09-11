<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="Robots" contect="none">
    <title>虹软智图</title>

    <link href="{{ asset('/bower-assets/bootswatch-ustclugFonts/flatly/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower-assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/style/app-dashboard.css') }}" rel="stylesheet">
    <script src="{{ asset('/bower-assets/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/bower-assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/bower-assets/vue/dist/vue.js') }}"></script>

    <script src="{{ asset('/assets/javascript/helps.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @include('layouts.common')
</head>

<body>
<div id="section-notification" style="position:absolute; top:80px; right:20px; z-index:999;">
    <script>
      $(document).ready(function () {
        Vue.config.devtools = true;
        setTimeout(function () {
          $('#section-notification').fadeOut();
        }, 3000);
      });
    </script>
    {!! Notification::showAll() !!}
</div>


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('dashboard') }}">ArcInPic <sup>AI</sup></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if (Auth::admin()->check())
                    <li class="{{ Request::is('dashboard') ? 'active' : ''}}"><a
                                href="{{ url('dashboard') }}">{{ trans('dashboard.Trend') }}</a></li>
                    <li class="{{ Request::is('dashboard/timeline*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/timeline') }}">{{ trans('dashboard.Timeline') }}</a></li>
                    <li class="{{ Request::is('dashboard/topic*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/topic') }}">{{ trans('dashboard.Topic') }}</a></li>
                    <li class="{{ Request::is('dashboard/user*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/user') }}">{{ trans('dashboard.User') }}</a></li>
                    <li class="{{ Request::is('dashboard/report*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/report') }}">{{ trans('dashboard.report') }}</a></li>
                    <li class="{{ Request::is('dashboard/feedback*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/feedback') }}">{{ trans('dashboard.feedback') }}</a></li>
                    <li class="{{ Request::is('dashboard/setting*') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/setting') }}">{{ trans('dashboard.Setting') }}</a></li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::admin()->guest())
                    <li class="{{ Request::is('log-in') ? 'active' : ''}}"><a
                                href="{{ url('dashboard/log-in') }}">{{ trans('dashboard.Login') }}</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{ Auth::admin()->user()->nickname }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a target="_blank" href="/">{{ trans('dashboard.Go to domain WebApp') }}</a></li>
                            <li><a href="{{ url('dashboard/log-out') }}">{{ trans('dashboard.Logout') }}</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>


@yield('content')


@include('layouts.common')
<div style="display:none">
    <script src="https://s4.cnzz.com/z_stat.php?id=1260869881&web_id=1260869881" language="JavaScript"></script>

    <script>
      (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

      ga('create', 'UA-68392745-10', 'auto');
      ga('send', 'pageview');

    </script>
</div>

<!-- DaoVoice -->
<script>(function (i, s, o, g, r, a, m) {
    i["DaoVoiceObject"] = r;
    i[r] = i[r] || function () {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    a.charset = "utf-8";
    m.parentNode.insertBefore(a, m)
  })(window, document, "script", ('https:' == document.location.protocol ? 'https:' : 'http:') + "//widget.daovoice.io/widget/c66556f8.js", "daovoice")</script>
@if (Auth::admin()->guest())
    <script>
      daovoice('init', {
        app_id: "c66556f8",
      });
      daovoice('update');
    </script>
@else
    <script>
      daovoice('init', {
        app_id: "c66556f8",
        private_deployment: "1",
        domain: "{{ $_SERVER['HTTP_HOST'] }}",
        ip: "{{ Request::server('SERVER_ADDR') . ':' . Request::server('SERVER_PORT') }}",
        email: "{{ Auth::admin()->user()->email }}",
        user_id: "{{ Auth::admin()->user()->id }}",
        signed_up: {{ Auth::admin()->user()->created_at->getTimestamp() }},
        name: "{{ Auth::admin()->user()->nickname }}",
      });
      daovoice('update');
    </script>
@endif
</body>
</html>
