<?php
// Menerima raw data dari mesin fingerprint
$data = file_get_contents("php://input");

// Mesin akan menyertakan parameter SN (Serial Number) dan table di URL
$sn = isset($_GET['SN']) ? $_GET['SN'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : '';

// Jika data yang dikirim adalah log presensi (ATTLOG)
if ($table == 'ATTLOG' && !empty($data)) {
    
    // URL Web App Google Apps Script Anda
    $gasUrl = "https://script.google.com/macros/s/AKfycbwyOBx32CJhEcqpcbksSiaO8Mz3yY3JLILfHYGNZIe11DL7dPYCiNHZsL8EuxrBDRrBDA/exec";
    
    // Mesin mengirim data dalam format baris teks (ID \t Waktu \t Status)
    $lines = explode("\n", trim($data));
    
    foreach ($lines as $line) {
        $parts = explode("\t", $line);
        
        if (count($parts) >= 3) {
            $userId = trim($parts[0]);
            $waktu = trim($parts[1]); // Format standar: YYYY-MM-DD HH:MM:SS
            $statusMesin = trim($parts[2]); 
            
            // Konversi kode mesin ke teks (Biasanya 0 = Masuk, 1 = Pulang)
            $tipe = ($statusMesin == "0") ? "Masuk" : "Pulang";
            
            // Siapkan payload JSON untuk dikirim ke Google Apps Script
            $payload = json_encode([
                "userId" => $userId,
                "waktu" => $waktu,
                "tipe" => $tipe
            ]);
            
            // Proses cURL ke Google Apps Script
            $ch = curl_init($gasUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}

// WAJIB: Server harus membalas dengan "OK" agar mesin tahu data berhasil diterima.
// Jika tidak ada "OK", mesin akan terus mengirim data yang sama berulang-ulang.
header("Content-Type: text/plain");
echo "OK\n";
?>
