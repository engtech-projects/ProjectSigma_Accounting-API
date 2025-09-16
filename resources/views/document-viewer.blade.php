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
            background-color: #9d9d9d;
            color: rgb(49, 51, 52);
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
            background-color: #9d9d9d;
        }
        .content {
            display: none;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            background: #f9f9f9;
            height: 100vh;
            overflow-y: auto;
        }
        .file-row {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 8px;
        }
        .file-row a {
            text-decoration: none;
            color: #337ab7;
        }
        .file-row a:hover {
            text-decoration: underline;
        }
        .file-link {
            display: block;
            padding: 8px 12px;
            margin: 0;
            background: #f8f9fa;
            border-radius: 4px 4px 0 0;
            color: #212529;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid #dee2e6;
        }
        .file-link:hover {
            background: #e9ecef;
            transform: translateX(2px);
        }
        .image-display {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>{{ $title }}</h1>

    @if ($publicFilePaths)
        @php
            $grouped = collect($publicFilePaths)->groupBy(function ($item) {
                return strtoupper(substr(strrchr($item, '.'), 1));
            });
        @endphp

        @foreach ($grouped as $type => $files)
            <button class="collapsible">{{ $type }} Files ({{ count($files) }})</button>
            <div class="content">
                @foreach ($files as $file)
                    <div class="file-row">
                        @if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpeg', 'jpg', 'png']))
                            <iframe src="{{ $file }}" class="image-display"></iframe>
                        @else
                            <a href="{{ $file }}" target="_blank" class="file-link">{{ basename($file) }}</a>
                        @endif
                    </div>
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
