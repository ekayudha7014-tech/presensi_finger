<?php
session_start();

// Konfigurasi Login Admin Sederhana
$admin_user = "admin";
$admin_pass = "admin123";

// Proses Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Proses Login
$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- URL Web App GAS Anda (Pastikan menggunakan URL Deployment terbaru) -->
    <script>
        const GAS_URL = "https://script.google.com/macros/s/AKfycbwyOBx32CJhEcqpcbksSiaO8Mz3yY3JLILfHYGNZIe11DL7dPYCiNHZsL8EuxrBDRrBDA/exec";
    </script>
</head>
<body class="bg-gray-100 font-sans m-0 p-0">

    <?php if (!isset($_SESSION['is_admin'])): ?>
    <!-- Halaman Login -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login Admin</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm text-center"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                </div>
                <button type="submit" name="login" class="w-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition">Masuk</button>
            </form>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Halaman Dashboard Admin -->
    
    <header class="w-full bg-blue-800 text-white p-4 shadow-md m-0 block">
        <div class="flex justify-between items-center px-4">
            <h1 class="text-xl font-bold uppercase">Manajemen Data Presensi</h1>
            <a href="?logout=true" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm font-medium transition">Logout</a>
        </div>
    </header>

    <main class="p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Log Kehadiran Pegawai</h2>
                <button onclick="loadData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm transition">
                    🔄 Refresh Data
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-left text-sm uppercase">
                            <th class="py-3 px-4 border-b">No</th>
                            <th class="py-3 px-4 border-b">Waktu</th>
                            <th class="py-3 px-4 border-b">ID Pegawai</th>
                            <th class="py-3 px-4 border-b">Tipe</th>
                            <th class="py-3 px-4 border-b">Sumber</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-sm text-gray-700">
                        <tr>
                            <td colspan="5" class="py-4 text-center">Memuat data dari Google Sheets...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Fungsi untuk mengambil data dari Google Apps Script
        async function loadData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-blue-600">Mengambil pembaruan data...</td></tr>';
            
            try {
                // Memanggil GAS URL dengan parameter action=get_data
                const response = await fetch(GAS_URL + "?action=get_data");
                const result = await response.json();
                
                if (result.status === 'success') {
                    const data = result.data;
                    tableBody.innerHTML = ''; // Kosongkan tabel
                    
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" class="py-4 text-center">Belum ada data presensi.</td></tr>';
                        return;
                    }
                    
                    // Render baris data (dibalik agar data terbaru di atas)
                    data.reverse().forEach((row, index) => {
                        const tr = document.createElement('tr');
                        tr.className = "hover:bg-gray-50 border-b";
                        
                        // row[0] = Waktu, row[1] = User ID, row[2] = Tipe, row[3] = Sumber
                        tr.innerHTML = `
                            <td class="py-3 px-4">${index + 1}</td>
                            <td class="py-3 px-4">${row[0] || '-'}</td>
                            <td class="py-3 px-4 font-semibold">${row[1] || '-'}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded-full ${row[2] === 'Masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${row[2] || '-'}
                                </span>
                            </td>
                            <td class="py-3 px-4">${row[3] || '-'}</td>
                        `;
                        tableBody.appendChild(tr);
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-red-500">Error: ${result.message}</td></tr>`;
                }
            } catch (error) {
                tableBody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-red-500">Gagal terhubung ke Google Sheets. Pastikan URL GAS benar.</td></tr>`;
                console.error(error);
            }
        }

        // Panggil fungsi saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', loadData);
    </script>
    <?php endif; ?>
</body>
</html>
