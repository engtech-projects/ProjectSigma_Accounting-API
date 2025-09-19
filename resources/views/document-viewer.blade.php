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
            max-height: 90dvh;
            overscroll-behavior: contain;
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
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .file-link:hover {
            background: #e9ecef;
            transform: translateX(2px);
        }
        .image-display {
            display: block;
            max-width: 100%;
            height: auto;
            max-height: 85dvh;
            object-fit: contain;
        }
        @media (max-width: 768px) {
            .image-display {
                height: 70dvh;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>{{ $title }}</h1>

    @if ($publicFilePaths)
        @php
            $grouped = collect($publicFilePaths)->groupBy(function ($item) {
                $path = parse_url($item, PHP_URL_PATH) ?? '';
                $ext  = pathinfo($path, PATHINFO_EXTENSION) ?: '';
                return $ext !== '' ? strtoupper($ext) : 'OTHER';
            });
        @endphp

        @foreach ($grouped as $type => $files)
            <button class="collapsible">{{ $type }} Files ({{ count($files) }})</button>
            <div class="content">
                @foreach ($files as $file)
                    @php
                        $path   = parse_url($file, PHP_URL_PATH) ?? '';
                        $scheme = parse_url($file, PHP_URL_SCHEME) ?? null;
                        $ext    = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $name   = basename($path);
                    @endphp
                    @continue($scheme && !in_array($scheme, ['http','https']))
                    <div class="file-row">
                        @if (in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'gif']))
                            <img
                                src="{{ $file }}"
                                alt="{{ $name }}"
                                class="image-display"
                                loading="lazy"
                                decoding="async"
                                referrerpolicy="no-referrer"
                            />
                        @else
                            <a href="{{ $file }}" target="_blank" rel="noopener noreferrer" class="file-link">{{ $name }}</a>
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
