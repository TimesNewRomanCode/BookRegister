<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BookRegister API Docs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.4.1/swagger-ui.css" />
    <style>body { margin:0; padding:0; }</style>
</head>
<body>
<div id="swagger-ui"></div>
<div id="swagger-error" style="color:#900; padding:20px; display:none;"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.4.1/swagger-ui-bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.4.1/swagger-ui-standalone-preset.js"></script>
<script>
window.onerror = function(message, source, lineno, colno, error) {
    var el = document.getElementById('swagger-error');
    if (el) {
        el.style.display = 'block';
        el.textContent = 'JS error: ' + message;
    }
};
window.onload = function() {
    SwaggerUIBundle({
        url: '/docs/api.json',
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        layout: 'BaseLayout',
        validatorUrl: null,
        requestInterceptor: function(request) {
            if (request.loadSpec) {
                request.headers['Accept'] = 'application/json';
            }
            return request;
        }
    });
};
</script>
</body>
</html>
