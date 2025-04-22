<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/boxicons.min.css" rel="stylesheet">
    <script src="/js/theme.js"></script>
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg" href="/img/logo_icon.svg">
    <script>
        window.api_url = '{{ env('APP_URL') }}/api/';
        window.csrf_token = '{{ csrf_token() }}';

        function makeRequest(url, method, callback, error, form_id = null) {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let responseData = null;
                        try {
                            responseData = JSON.parse(xhr.responseText);
                        } catch (e) {
                            responseData = xhr.responseText;
                        }
                        callback(responseData);
                    } else {
                        let errorMessage = `Error ${xhr.status}: ${xhr.statusText}`;
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                            error();
                        } catch (e) {
                            error();
                        }
                    }
                }
            };

            xhr.open(method, url, true);

            if (form_id) {
                const form = document.getElementById(form_id);
                const formData = new FormData(form);

                let hasFile = false;
                for (const entry of formData.entries()) {
                    const [key, value] = entry;
                    if (form.elements[key].type === 'file' && form.elements[key].value !== '') {
                        hasFile = true;
                        break;
                    }
                }
                if (hasFile) {
                    xhr.send(formData);
                } else {
                    let jsonData = {};
                    formData.forEach((value, key) => {
                        if (form.elements[key].type === 'checkbox') {
                            jsonData[key] = form.elements[key].checked;
                        } else {
                            jsonData[key] = value;
                        }
                    });

                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.send(JSON.stringify(jsonData));
                }
            } else {
                xhr.send();
            }
        }

        function showToast(message = '') {
            let toast = document.getElementById('liveToast');

            if(!toast){
                let html = `
                        <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header d-flex justify-content-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                </div>
                            </div>
                        </div>
                    `;

                let element = document.createElement('div');
                element.innerHTML = html;
                document.body.appendChild(element);
                toast = document.getElementById('liveToast');
            }
            
            const toast_bootstrap = bootstrap.Toast.getOrCreateInstance(toast);

            toast.querySelector('.toast-body').innerText = message;

            toast_bootstrap.show();
        }

        function closeToast() {
            let toast = document.getElementById('liveToast');
            if(toast){
                const toast_bootstrap = bootstrap.Toast.getOrCreateInstance(toast);
                toast_bootstrap.hide();
            }
        }

    </script>
    @vite('resources/js/app.js')
    @routes
</head>
<body id="body-pd">
    @inertia
</body>
<script src="/js/bootstrap.bundle.js"></script>
</html>