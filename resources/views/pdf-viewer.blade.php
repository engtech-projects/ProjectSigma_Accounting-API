<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA PDF-VIEWER</title>
    <style>
        html, body {
            padding: 0;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            width: auto;
            margin: auto;
            padding: 20px;
        }
        .collapsible {
            background-color: #204d43;
            color: white;
            cursor: pointer;
            padding: 15px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 18px;
            transition: 0.3s;
            border-radius: 5px;
            margin-top: 10px;
        }
        .collapsible:hover {
            background-color: #2b9780;
        }
        .content {
            display: none;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            background: #f9f9f9;
        }
        iframe {
            width: 100%;
            height: 500px;
            border: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>{{ $title }}</h1>

    @if ($publicFilePaths)
        @php
            $grouped = collect($publicFilePaths)->groupBy(function ($item) {
                return strtoupper(substr(strrchr($item, '.'), 1)); // Extracts file extension and converts it to uppercase
            });
        @endphp

        @foreach ($grouped as $type => $files)
            <button class="collapsible">{{ $type }} Files ({{ count($files) }})</button>
            <div class="content">
                @foreach ($files as $file)
                    @if (in_array($type, ['PDF', 'JPEG', 'JPG', 'PNG']))
                        <iframe src="{{ $file }}"></iframe>
                    @else
                        <p><a href="{{ $file }}" target="_blank">{{ basename($file) }}</a></p>
                    @endif
                @endforeach
            </div>
        @endforeach
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var collapsibles = document.querySelectorAll(".collapsible");

        collapsibles.forEach(button => {
            button.addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        });
    });
</script>

</body>
</html>
