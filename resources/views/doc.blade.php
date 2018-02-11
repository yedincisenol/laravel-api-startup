<!DOCTYPE html>
<html>
<head>
    <title>Laravel Api Startup</title>
    <link rel="stylesheet" href="main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.vaadin.com/vaadin-core-elements/1.2.0/webcomponentsjs/webcomponents-lite.min.js"></script>
    <link rel="import" href="https://cdn.vaadin.com/vaadin-core-elements/1.2.0/vaadin-combo-box/vaadin-combo-box-light.html">
</head>
<body>
<nav>
    <iframe src="https://ghbtns.com/github-btn.html?user=yedincisenol&repo=laravel-api-startup&type=star&count=true&size=large"
            frameborder="0" scrolling="0" width="150px" height="30px"></iframe>
</nav>

<redoc scroll-y-offset="body > nav" spec-url='swagger.yaml' lazy-rendering untrusted-spec></redoc>

<script src="http://rebilly.github.io/ReDoc/dist/redoc.min.js"> </script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-81703547-1', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
