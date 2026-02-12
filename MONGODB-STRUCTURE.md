# 📊 MongoDB Database Structure

Dokumentasi lengkap struktur database MongoDB untuk Toko Online.

---

## 🗄️ Database: `toko_online`

Database utama yang menyimpan semua data aplikasi.

---

## 📑 Collections

### 1. Collection: `users`

**Deskripsi:** Menyimpan data pengguna (admin dan user biasa)

**Schema:**
```javascript
{
  "_id": ObjectId("507f1f77bcf86cd799439011"),
  "name": String,              // Nama lengkap user
  "email": String,             // Email (unique)
  "password": String,          // Hashed password dengan bcrypt
  "role": String,              // "admin" atau "user"
  "created_at": ISODate("2026-02-12T10:30:00Z")
}
```

**Indexes:**
```javascript
{
  "email": 1  // Unique index
}
```

**Contoh Document:**
```javascript
{
  "_id": ObjectId("65c9f123abc456def7890123"),
  "name": "Admin Toko",
  "email": "admin@toko.com",
  "password": "$2y$10$abcdefghijklmnopqrstuvwxyz1234567890",
  "role": "admin",
  "created_at": ISODate("2026-02-12T08:00:00Z")
}
```

**Validation Rules:**
- `email` harus unik
- `password` minimal 6 karakter (sebelum di-hash)
- `role` default: "user"
- `created_at` auto-generate dengan `new MongoDB\BSON\UTCDateTime()`

**Queries Umum:**

```php
// Find user by email
$user = $usersCollection->findOne(['email' => 'admin@toko.com']);

// Create new user
$usersCollection->insertOne([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'role' => 'user',
    'created_at' => new MongoDB\BSON\UTCDateTime()
]);

// Count total users
$totalUsers = $usersCollection->countDocuments();

// Count admins only
$totalAdmins = $usersCollection->countDocuments(['role' => 'admin']);

// Get all users sorted by created date
$users = $usersCollection->find([], ['sort' => ['created_at' => -1]]);
```

---

### 2. Collection: `products`

**Deskripsi:** Menyimpan data produk yang dijual di toko

**Schema:**
```javascript
{
  "_id": ObjectId("507f1f77bcf86cd799439012"),
  "name": String,              // Nama produk
  "price": Number,             // Harga dalam Rupiah (float/integer)
  "stock": Number,             // Jumlah stock (integer)
  "description": String,       // Deskripsi produk (text)
  "created_at": ISODate("2026-02-12T10:30:00Z"),
  "updated_at": ISODate("2026-02-12T10:30:00Z")
}
```

**Indexes:**
```javascript
// Bisa ditambahkan untuk optimasi:
{
  "name": "text",           // Full-text search
  "created_at": -1          // Sort by newest
}
```

**Contoh Document:**
```javascript
{
  "_id": ObjectId("65c9f456def123abc7890456"),
  "name": "Laptop Gaming ROG",
  "price": 25000000,
  "stock": 5,
  "description": "Laptop gaming dengan processor Intel Core i9, RAM 32GB, RTX 4080",
  "created_at": ISODate("2026-02-12T08:30:00Z"),
  "updated_at": ISODate("2026-02-12T08:30:00Z")
}
```

**Validation Rules:**
- `name` wajib diisi
- `price` harus > 0
- `stock` default: 0
- `created_at` dan `updated_at` auto-manage

**Queries Umum:**

```php
// Get all products sorted by newest
$products = $productsCollection->find([], ['sort' => ['created_at' => -1]]);

// Add new product
$productsCollection->insertOne([
    'name' => 'Mouse Gaming',
    'price' => 500000,
    'stock' => 10,
    'description' => 'Mouse gaming RGB dengan sensor optical',
    'created_at' => new MongoDB\BSON\UTCDateTime(),
    'updated_at' => new MongoDB\BSON\UTCDateTime()
]);

// Update product
$productsCollection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($productId)],
    ['$set' => [
        'name' => 'New Name',
        'price' => 600000,
        'updated_at' => new MongoDB\BSON\UTCDateTime()
    ]]
);

// Delete product
$productsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);

// Count total products
$totalProducts = $productsCollection->countDocuments();

// Find products with stock > 0
$inStockProducts = $productsCollection->find(['stock' => ['$gt' => 0]]);

// Search products by name (requires text index)
$results = $productsCollection->find([
    '$text' => ['$search' => 'laptop']
]);
```

---

## 🔧 MongoDB Operations Cheatsheet

### Insert Operations

```php
// Insert One
$result = $collection->insertOne([
    'field1' => 'value1',
    'field2' => 'value2'
]);
$insertedId = $result->getInsertedId();

// Insert Many
$result = $collection->insertMany([
    ['field1' => 'value1'],
    ['field1' => 'value2']
]);
$insertedCount = $result->getInsertedCount();
```

### Find Operations

```php
// Find One
$document = $collection->findOne(['email' => 'user@example.com']);

// Find All
$cursor = $collection->find([]);

// Find with Filter
$cursor = $collection->find(['role' => 'admin']);

// Find with Sort
$cursor = $collection->find([], ['sort' => ['created_at' => -1]]);

// Find with Limit
$cursor = $collection->find([], ['limit' => 10]);

// Find with Skip (Pagination)
$cursor = $collection->find([], [
    'limit' => 10,
    'skip' => 20
]);

// Count Documents
$count = $collection->countDocuments(['role' => 'admin']);
```

### Update Operations

```php
// Update One
$result = $collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => ['field' => 'new value']]
);
$modifiedCount = $result->getModifiedCount();

// Update Many
$result = $collection->updateMany(
    ['role' => 'user'],
    ['$set' => ['status' => 'active']]
);

// Increment value
$collection->updateOne(
    ['_id' => $id],
    ['$inc' => ['stock' => 1]]
);
```

### Delete Operations

```php
// Delete One
$result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
$deletedCount = $result->getDeletedCount();

// Delete Many
$result = $collection->deleteMany(['role' => 'user']);
```

---

## 🎯 Advanced Queries

### Comparison Operators

```php
// Greater than
$products = $productsCollection->find(['price' => ['$gt' => 1000000]]);

// Less than
$products = $productsCollection->find(['price' => ['$lt' => 1000000]]);

// Greater than or equal
$products = $productsCollection->find(['stock' => ['$gte' => 5]]);

// Less than or equal
$products = $productsCollection->find(['stock' => ['$lte' => 5]]);

// Not equal
$users = $usersCollection->find(['role' => ['$ne' => 'admin']]);

// In array
$products = $productsCollection->find([
    'price' => ['$in' => [500000, 1000000, 1500000]]
]);
```

### Logical Operators

```php
// AND (implicit)
$products = $productsCollection->find([
    'price' => ['$gt' => 1000000],
    'stock' => ['$gt' => 0]
]);

// OR
$products = $productsCollection->find([
    '$or' => [
        ['price' => ['$lt' => 500000]],
        ['stock' => 0]
    ]
]);

// NOT
$products = $productsCollection->find([
    'price' => ['$not' => ['$gt' => 1000000]]
]);
```

### Aggregation Pipeline

```php
// Get average price
$result = $productsCollection->aggregate([
    ['$group' => [
        '_id' => null,
        'avgPrice' => ['$avg' => '$price']
    ]]
]);

// Count products by price range
$result = $productsCollection->aggregate([
    ['$bucket' => [
        'groupBy' => '$price',
        'boundaries' => [0, 1000000, 5000000, 10000000],
        'default' => 'Other',
        'output' => ['count' => ['$sum' => 1]]
    ]]
]);
```

---

## 🔐 Index Management

### Create Indexes

```php
// Unique index
$usersCollection->createIndex(['email' => 1], ['unique' => true]);

// Text index for search
$productsCollection->createIndex(['name' => 'text', 'description' => 'text']);

// Compound index
$productsCollection->createIndex([
    'price' => 1,
    'stock' => -1
]);

// List all indexes
$indexes = $collection->listIndexes();
foreach ($indexes as $index) {
    echo $index->getName() . "\n";
}
```

---

## 📈 Performance Tips

### 1. Use Indexes
```php
// Create index on frequently queried fields
$usersCollection->createIndex(['email' => 1], ['unique' => true]);
$productsCollection->createIndex(['created_at' => -1]);
```

### 2. Projection (Select specific fields only)
```php
// Only get name and price, exclude _id
$products = $productsCollection->find([], [
    'projection' => [
        'name' => 1,
        'price' => 1,
        '_id' => 0
    ]
]);
```

### 3. Limit Results
```php
// Get only 10 latest products
$products = $productsCollection->find([], [
    'sort' => ['created_at' => -1],
    'limit' => 10
]);
```

### 4. Use Aggregation for Complex Queries
```php
// Instead of multiple queries
$stats = $productsCollection->aggregate([
    ['$group' => [
        '_id' => null,
        'total' => ['$sum' => 1],
        'avgPrice' => ['$avg' => '$price'],
        'totalStock' => ['$sum' => '$stock']
    ]]
]);
```

---

## 🛡️ Security Best Practices

### 1. Password Hashing
```php
// Always hash passwords
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Verify password
if (password_verify($inputPassword, $user['password'])) {
    // Login success
}
```

### 2. Input Validation
```php
// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email');
}

// Sanitize inputs
$name = trim(htmlspecialchars($_POST['name']));
```

### 3. Use ObjectId Safely
```php
// Always validate ObjectId
try {
    $objectId = new MongoDB\BSON\ObjectId($id);
} catch (Exception $e) {
    // Invalid ObjectId format
    return false;
}
```

---

## 📊 Example Database Queries

### User Management

```php
// Register new user
$usersCollection->insertOne([
    'name' => trim($_POST['name']),
    'email' => trim($_POST['email']),
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'role' => 'user',
    'created_at' => new MongoDB\BSON\UTCDateTime()
]);

// Login check
$user = $usersCollection->findOne(['email' => $email]);
if ($user && password_verify($password, $user['password'])) {
    // Set session
    setUserSession($user);
}

// Get user by ID
$user = $usersCollection->findOne([
    '_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])
]);
```

### Product Management

```php
// Add product
$productsCollection->insertOne([
    'name' => $_POST['name'],
    'price' => floatval($_POST['price']),
    'stock' => intval($_POST['stock']),
    'description' => $_POST['description'],
    'created_at' => new MongoDB\BSON\UTCDateTime(),
    'updated_at' => new MongoDB\BSON\UTCDateTime()
]);

// Update product
$productsCollection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => [
        'name' => $_POST['name'],
        'price' => floatval($_POST['price']),
        'stock' => intval($_POST['stock']),
        'description' => $_POST['description'],
        'updated_at' => new MongoDB\BSON\UTCDateTime()
    ]]
);

// Delete product
$productsCollection->deleteOne([
    '_id' => new MongoDB\BSON\ObjectId($id)
]);

// Get products with pagination
$page = intval($_GET['page'] ?? 1);
$limit = 12;
$skip = ($page - 1) * $limit;

$products = $productsCollection->find([], [
    'sort' => ['created_at' => -1],
    'limit' => $limit,
    'skip' => $skip
]);

$totalProducts = $productsCollection->countDocuments();
$totalPages = ceil($totalProducts / $limit);
```

---

## 🔄 Data Migration

### Backup Database
```bash
# Using MongoDB Atlas UI
# Go to Clusters → ... → Export Data

# Or using mongodump (if you have MongoDB tools)
mongodump --uri="mongodb+srv://user:pass@cluster.mongodb.net/toko_online" --out=/backup
```

### Restore Database
```bash
mongorestore --uri="mongodb+srv://user:pass@cluster.mongodb.net/toko_online" /backup/toko_online
```

---

**Dokumentasi ini mencakup semua operasi CRUD dan query yang digunakan dalam aplikasi Toko Online MongoDB.** 📚
