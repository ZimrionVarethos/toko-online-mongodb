<?php
require_once __DIR__ . '/../config/session.php';

// Clear session and redirect to home
clearUserSession();
setFlashMessage('success', 'Anda telah berhasil logout');
header('Location: /index.php');
exit();
