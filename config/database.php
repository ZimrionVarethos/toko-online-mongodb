<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/env.php';

// Load environment variables
loadEnv(__DIR__ . '/../.env');

use MongoDB\Client;
use MongoDB\Driver\ServerApi;

class Database {
    private static $instance = null;
    private $client;
    private $database;
    
    private function __construct() {
        try {
            $mongoUri = env('MONGO_URI');
            $dbName = env('DB_NAME', 'toko_online');
            
            if (!$mongoUri) {
                throw new Exception('MONGO_URI not found in .env file');
            }
            
            // Create MongoDB client with Server API version
            $serverApi = new ServerApi(ServerApi::V1);
            $this->client = new Client($mongoUri, [], ['serverApi' => $serverApi]);
            
            // Select database
            $this->database = $this->client->selectDatabase($dbName);
            
            // Test connection
            $this->client->selectDatabase('admin')->command(['ping' => 1]);
            
        } catch (Exception $e) {
            die("MongoDB Connection Error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getDatabase() {
        return $this->database;
    }
    
    public function getCollection($collectionName) {
        return $this->database->selectCollection($collectionName);
    }
}

// Helper function to get database instance
function getDB() {
    return Database::getInstance()->getDatabase();
}

// Helper function to get collection
function getCollection($name) {
    return Database::getInstance()->getCollection($name);
}
