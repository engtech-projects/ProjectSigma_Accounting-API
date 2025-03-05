<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA PDF-VIEWER</title>
    <style>
        html, body {
            padding: 0%;
            margin: 0%;
        }
        iframe {
            width: 100%;
            height: 98vh;
            border: none;
        }
    </style>
</head>
<body>
    @if ($fileType === 'pdf')
        <div>
            <iframe src="{{ $pdfPath }}" frameborder="0" width="100%" height="800"></iframe>
        </div>
    @elseif ($fileType === 'image')
        <img src="{{ $pdfPath }}" alt="Image Preview" width="100%" height="800">
    @else
        <p>File not found.</p>
    @endif

</body>
</html>
