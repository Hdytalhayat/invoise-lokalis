<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvoiseLokalis - Invoice untuk UMKM Lokal</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font sans-serif yang bersih -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-blue-600">InvoiseLokalis</h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="/" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                        <li><a href="/login" class="hover:text-blue-600 transition-colors">Login</a></li>
                        <li><a href="/register" class="hover:text-blue-600 transition-colors">Daftar</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-slate-600 text-sm">
            <p>&copy; <?= date('Y') ?> InvoiseLokalis - Invoice untuk UMKM Lokal. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>
</html>