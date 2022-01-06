<!doctype html>
<title>{{ env('APP_NAME') }} - Error</title>
<style>
    body { text-align: center; padding: 150px; }
    h1 { font-size: 50px; }
    body { font: 20px Helvetica, sans-serif; color: #333; }
    article { display: block; text-align: left; width: 650px; margin: 0 auto; }
    a { color: #dc8100; text-decoration: none; }
    a:hover { color: #333; text-decoration: none; }
</style>

<article>
    <h1>Error 502</h1>
    <div>
        <p>Sorry for the inconvenience but we&rsquo;re experiencing some issues, to contact us please call <a href="tel:{{ env('SUPPORT_PHONE') }}">call us</a> or <a href="mailto:{{ env('SUPPORT_EMAIL') }}">email us</a>, otherwise we&rsquo;ll be back online shortly!</p>
        <p>&mdash; {{ env('COMPANY_NAME') }}</p>
    </div>
</article>

