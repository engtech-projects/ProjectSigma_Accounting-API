<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA ATTACHMENTS</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            padding: 0;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        h1 {
            color: #ffffff;
            font-size: 3rem;
            font-weight: 800;
            margin: 0 0 15px 0;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #e0f2fe 0%, #7ee8c0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            color: #cbd5e1;
            font-size: 1.1rem;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        .file-groups {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .group-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .group-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15), 0 10px 15px -5px rgba(0, 0, 0, 0.06);
        }

        .group-header {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: background 0.3s ease;
            border: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-size: 1rem;
        }

        .group-card.collapsed .group-header {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        .group-header:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        }

        .group-title-section {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .file-icon {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            flex-shrink: 0;
        }

        .group-info h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .group-count {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .expand-icon {
            color: white;
            font-size: 1.4rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .group-card.collapsed .expand-icon {
            transform: rotate(-90deg);
        }

        .group-content {
            padding: 24px;
            display: grid;
            gap: 16px;
            max-height: 800px;
            overflow-y: auto;
            transition: max-height 0.3s ease;
        }

        .group-card.collapsed .group-content {
            display: none;
        }

        .group-content::-webkit-scrollbar {
            width: 8px;
        }

        .group-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .group-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .group-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .file-row {
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #cfe6ea;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .file-row:hover {
            background: #e0f2fe;
            border-left-color: #0891b2;
            transform: translateX(6px);
        }

        .image-display {
            display: block;
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .image-display:hover {
            transform: scale(1.03);
        }

        .file-link {
            display: block;
            color: #0369a1;
            text-decoration: none;
            font-weight: 600;
            word-break: break-word;
            overflow-wrap: anywhere;
            transition: color 0.2s ease;
            font-size: 0.95rem;
        }

        .file-link:hover {
            color: #0891b2;
            text-decoration: underline;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 40px;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0;
            font-size: 1.1rem;
        }

        @media (max-width: 1024px) {
            .file-groups {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
            }

            h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .file-groups {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .group-header {
                padding: 18px;
            }

            .group-content {
                padding: 16px;
            }

            .file-row {
                padding: 12px 14px;
            }

            .file-icon {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }

            .group-info h3 {
                font-size: 1.1rem;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .group-card {
            animation: fadeIn 0.5s ease-out;
        }

        .group-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .group-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .group-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>SIGMA ACCOUNTING Attachments</h1>
        <p class="subtitle">Organize and manage all your files in one place</p>
    </div>

    <div class="file-groups" id="fileGroups">
        <!-- File groups will be populated here -->
    </div>
</div>

<script>
    // Pass your publicFilePaths array from Laravel here
    const publicFilePaths = @json($publicFilePaths);
    const typeIcons = {
        'PDF': '📄',
        'IMAGES': '🖼️',
        'JPG': '🖼️',
        'JPEG': '🖼️',
        'PNG': '🖼️',
        'GIF': '🖼️',
        'WEBP': '🖼️',
        'SPREADSHEET': '📊',
        'XLSX': '📊',
        'XLS': '📊',
        'CSV': '📊',
        'VIDEO': '🎬',
        'MP4': '🎬',
        'MOV': '🎬',
        'AVI': '🎬',
        'AUDIO': '🎵',
        'MP3': '🎵',
        'WAV': '🎵',
        'ARCHIVE': '📦',
        'ZIP': '📦',
        'RAR': '📦',
        'CODE': '💻',
        'JS': '💻',
        'PY': '💻',
        'HTML': '💻',
        'CSS': '💻',
        'OTHER': '📎'
    };

    function getIcon(type) {
        return typeIcons[type] || typeIcons.OTHER;
    }

    function parseUrl(url) {
        try {
            const urlObj = new URL(url);
            return {
                pathname: urlObj.pathname,
                scheme: urlObj.protocol.replace(':', '')
            };
        } catch {
            return {
                pathname: url,
                scheme: null
            };
        }
    }

    function groupFiles(files) {
        const grouped = {};

        files.forEach(file => {
            const { pathname } = parseUrl(file);
            const ext = pathname.split('.').pop().toUpperCase() || 'OTHER';
            const type = ext !== '' ? ext : 'OTHER';

            if (!grouped[type]) {
                grouped[type] = [];
            }
            grouped[type].push(file);
        });

        return grouped;
    }

    function renderFiles() {
        const container = document.getElementById('fileGroups');

        if (!publicFilePaths || publicFilePaths.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📁</div>
                    <p>No files available</p>
                </div>
            `;
            return;
        }

        const grouped = groupFiles(publicFilePaths);

        container.innerHTML = Object.entries(grouped)
            .sort((a, b) => a[0].localeCompare(b[0]))
            .map(([type, files]) => {
                const fileRows = files
                    .filter(file => {
                        const { scheme } = parseUrl(file);
                        return !scheme || ['http', 'https'].includes(scheme);
                    })
                    .map(file => {
                        const { pathname } = parseUrl(file);
                        const ext = pathname.split('.').pop().toLowerCase();
                        const name = pathname.split('/').pop();
                        const imageExts = ['jpeg', 'jpg', 'png', 'webp', 'gif'];

                        if (imageExts.includes(ext)) {
                            return `
                                <div class="file-row">
                                    <img
                                        src="${file}"
                                        alt="${name}"
                                        class="image-display"
                                        loading="lazy"
                                        decoding="async"
                                        referrerpolicy="no-referrer"
                                    />
                                </div>
                            `;
                        }

                        return `
                            <div class="file-row">
                                <a href="${file}" target="_blank" rel="noopener noreferrer" class="file-link">
                                    ${name}
                                </a>
                            </div>
                        `;
                    })
                    .join('');

                return `
                    <div class="group-card">
                        <button class="group-header" onclick="toggleGroup(this)">
                            <div class="group-title-section">
                                <div class="file-icon">${getIcon(type)}</div>
                                <div class="group-info">
                                    <h3>${type} Files</h3>
                                    <span class="group-count">${files.length} ${files.length === 1 ? 'file' : 'files'}</span>
                                </div>
                            </div>
                            <div class="expand-icon">▶</div>
                        </button>
                        <div class="group-content">
                            ${fileRows}
                        </div>
                    </div>
                `;
            })
            .join('');
    }

    function toggleGroup(header) {
        const card = header.parentElement;
        card.classList.toggle('collapsed');
    }

    // Initial render
    renderFiles();
</script>

</body>
</html>
