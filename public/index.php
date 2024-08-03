<?php
require '../vendor/autoload.php';

use App\TwoFactorAuth;

session_start();

// Initialize 2FA class
$twoFA = new TwoFactorAuth();

// Generate or retrieve the secret
if (!isset($_SESSION['secret'])) {
    $_SESSION['secret'] = $twoFA->generateSecret();
}
$secret = $_SESSION['secret'];

// Generate QR code URL
$qrCodeUrl = $twoFA->getQRCodeUrl('admin_dashboard', $secret, 'Your Company Name');

// Handle OTP verification
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'] ?? '';
    if ($twoFA->verifyCode($secret, $otp)) {
        $message = "Authentication successful.";
    } else {
        $message = "Invalid OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - 2FA</title>
</head>
<body>
    <h1>Admin Dashboard 2FA Login</h1>
    <p>Scan the QR code with your Google Authenticator app:</p>
    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code"><br>
    <p>Secret: <?php echo $secret; ?></p>

    <form method="post">
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" id="otp" required>
        <button type="submit">Verify</button>
    </form>

    <p><?php echo $message; ?></p>
</body>
</html>