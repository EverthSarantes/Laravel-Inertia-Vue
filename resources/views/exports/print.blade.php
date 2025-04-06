<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        br:not(.not-break) {
            break-before: always;
        }

        @page {
            size: {{ isset($pageProperties['size']) ? $pageProperties['size'] : 'letter' }} {{ isset($pageProperties['orientation']) ? $pageProperties['orientation'] : 'portrait' }};
            margin-top: {{ isset($pageProperties['margin-top']) ? $pageProperties['margin-top'] : '3.8cm' }};
            margin-bottom: {{ isset($pageProperties['margin-bottom']) ? $pageProperties['margin-bottom'] : '1.5cm' }};
            margin-left: {{ isset($pageProperties['margin-left']) ? $pageProperties['margin-left'] : '1.5cm' }};
            margin-right: {{ isset($pageProperties['margin-right']) ? $pageProperties['margin-right'] : '1.5cm' }};
            {{ isset($pageProperties['margin']) ? 'margin: ' . $pageProperties['margin'] : '' }}
        }

        @page {
            @if(isset($pageProperties['background']) && $pageProperties['background'] == true)
                background-image: url('');
                background-size: 100% auto;
                background-repeat: no-repeat;
            @endif

            @if(isset($pageProperties['pagecounter']) && $pageProperties['pagecounter'] == true)
                @bottom-center {
                    content: "Página " counter(page) " de " counter(pages);
                    margin-bottom: 10px;
                }
            @endif
        }
    </style>

    @stack('styles')
</head>

<body class="print-container" id="print-container">
    @if($params)
        @include($view_name, $params)
    @else
        @include($view_name)
    @endif
</body>

<script>
    window.loadPagedJs = {{isset($pageProperties['pagedjs']) && $pageProperties['pagedjs'] == true ? true : false}};
</script>

<script src="/js/exports/print.js"></script>
@stack('scripts')

</html>