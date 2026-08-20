<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi Geisa - Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 m-0 p-0 font-sans">

    <!-- Header Full Width -->
    <header class="w-full bg-blue-800 text-white py-5 px-8 shadow-md absolute top-0 left-0 right-0">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold uppercase tracking-wider">Aplikasi Presensi Pegawai</h1>
            <nav>
                <!-- Tombol Navigasi Admin -->
                <a href="admin.php" class="text-sm font-medium hover:text-blue-200 transition">Login Admin</a>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex flex-col items-center justify-center min-h-screen pt-20">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm border border-gray-100">
            <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Presensi Web</h2>
            
            <form id="formPresensi" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Pegawai</label>
                    <input type="text" id="userId" placeholder="Masukkan ID (Misal: 101)" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex space-x-3 mt-4">
                    <button type="button" onclick="submitPresensi('Masuk')" 
                            class="w-1/2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Masuk
                    </button>
                    <button type="button" onclick="submitPresensi('Pulang')" 
                            class="w-1/2 bg-rose-600 hover:bg-rose-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Pulang
                    </button>
                </div>
            </form>
            
            <div id="statusMessage" class="mt-4 text-center text-sm font-medium hidden"></div>
        </div>
    </main>

    <script>
        function submitPresensi(tipe) {
            const userId = document.getElementById('userId').value;
            const statusMsg = document.getElementById('statusMessage');
            
            if(!userId) {
                alert("ID Pegawai tidak boleh kosong!");
                return;
            }

            statusMsg.classList.remove('hidden');
            statusMsg.className = "mt-4 text-center text-sm font-medium text-blue-600";
            statusMsg.innerText = "Memproses...";

            // Anda bisa mengarahkan POST ini ke API Google Apps Script yang sama
            // menggunakan fetch API JavaScript di sini.
        }
    </script>
</body>
</html>
