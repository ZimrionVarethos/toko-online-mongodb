<?php
/**
 * Test MongoDB Connection
 * File ini untuk testing koneksi ke MongoDB Atlas
 * 
 * Cara menjalankan:
 * php test-connection.php
 */

echo "=== MongoDB Connection Test ===\n\n";

// Check if .env exists
if (!file_exists(__DIR__ . '/.env')) {
    echo "❌ File .env tidak ditemukan!\n";
    echo "\nLangkah:\n";
    echo "1. Copy .env.example menjadi .env\n";
    echo "   cp .env.example .env\n";
    echo "2. Edit .env dan isi MONGO_URI dengan connection string dari MongoDB Atlas\n";
    exit(1);
}

// Check if vendor exists
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "❌ Composer dependencies belum diinstall!\n";
    echo "\nJalankan:\n";
    echo "   composer install\n";
    exit(1);
}

require_once __DIR__ . '/config/database.php';

try {
    echo "🔄 Mencoba koneksi ke MongoDB...\n\n";
    
    $db = getDB();
    
    echo "✅ Koneksi berhasil!\n\n";
    echo "Database Information:\n";
    echo "-------------------\n";
    echo "Database Name: " . $db->getDatabaseName() . "\n";
    
    // List collections
    echo "\nCollections:\n";
    $collections = $db->listCollections();
    
    $hasCollections = false;
    foreach ($collections as $collection) {
        $hasCollections = true;
        $collectionName = $collection->getName();
        $count = $db->selectCollection($collectionName)->countDocuments();
        echo "  - " . $collectionName . " (" . $count . " documents)\n";
    }
    
    if (!$hasCollections) {
        echo "  (Belum ada collections)\n";
        echo "\n💡 Tip: Jalankan 'php setup-demo-data.php' untuk membuat demo data\n";
    }
    
    echo "\n✅ Test selesai!\n";
    
} catch (Exception $e) {
    echo "❌ Koneksi gagal!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "Troubleshooting:\n";
    echo "---------------\n";
    echo "1. Pastikan MONGO_URI di file .env sudah benar\n";
    echo "2. Pastikan username dan password sudah sesuai\n";
    echo "3. Pastikan IP address sudah ditambahkan di MongoDB Atlas Network Access\n";
    echo "4. Pastikan cluster MongoDB Atlas sudah aktif\n";
    echo "5. Cek koneksi internet\n";
    
    exit(1);
}
