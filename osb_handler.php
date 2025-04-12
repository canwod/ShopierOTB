<?php
// Veritabanı bağlantı bilgileri
define('DB_HOST', 'localhost');
define('DB_USER', 'kullanici_adi');
define('DB_PASS', 'sifre');
define('DB_NAME', 'veritabani_adi');

// OSB Kimlik bilgileri
$username = 'fac0f698b06cbc488e9fc2a09cbd341f';
$key = 'e8a789329828601e8ba4b50e913ba3f8';

// Gelen parametrelerin kontrolü
if (!((isset($_POST['res'])) && (isset($_POST['hash'])))) {
    error_log("OSB Hatası: Eksik parametreler");
    echo "missing parameter";
    die();
}

// Hash kontrolü
$hash = hash_hmac('sha256', $_POST['res'] . $username, $key, false);
if (strcmp($hash, $_POST['hash']) != 0) {
    error_log("OSB Hatası: Hash doğrulama başarısız");
    die();
}

// JSON verilerin çözümlenmesi
$json_result = base64_decode($_POST['res']);
$array_result = json_decode($json_result, true);

try {
    // Veritabanı bağlantısı
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Siparişin daha önce işlenip işlenmediğini kontrol et
    $stmt = $db->prepare("SELECT id FROM siparisler WHERE siparis_no = ?");
    $stmt->execute([$array_result['orderid']]);
    
    if ($stmt->rowCount() == 0) {
        // Yeni sipariş kaydı
        $sql = "INSERT INTO siparisler (
            siparis_no, email, para_birimi, fiyat, 
            musteri_adi, musteri_soyadi, urun_sayisi,
            urun_id, urun_listesi, sepet_detaylari,
            musteri_notu, test_mi, kayit_tarihi
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $array_result['orderid'],
            $array_result['email'],
            $array_result['currency'],
            $array_result['price'],
            $array_result['buyername'],
            $array_result['buyersurname'],
            $array_result['productcount'],
            $array_result['productid'],
            $array_result['productlist'],
            $array_result['chartdetails'],
            $array_result['customernote'],
            $array_result['istest']
        ]);

        // Sipariş e-postası gönder
        sendOrderEmail($array_result);
        
        error_log("OSB Bildirimi: Yeni sipariş kaydedildi - Sipariş No: " . $array_result['orderid']);
    } else {
        error_log("OSB Bildirimi: Sipariş zaten mevcut - Sipariş No: " . $array_result['orderid']);
    }

    echo "success";

} catch (PDOException $e) {
    error_log("OSB Veritabanı Hatası: " . $e->getMessage());
    die();
}

/**
 * Sipariş e-postası gönderme fonksiyonu
 */
function sendOrderEmail($orderData) {
    $to = $orderData['email'];
    $subject = "Siparişiniz Alındı - Sipariş No: " . $orderData['orderid'];
    
    $message = "Sayın " . $orderData['buyername'] . " " . $orderData['buyersurname'] . ",\n\n";
    $message .= "Siparişiniz başarıyla alınmıştır.\n";
    $message .= "Sipariş Numaranız: " . $orderData['orderid'] . "\n";
    $message .= "Toplam Tutar: " . $orderData['price'] . " " . 
                ($orderData['currency'] == 0 ? "TL" : ($orderData['currency'] == 1 ? "USD" : "EUR")) . "\n";
    
    $headers = "From: siparis@siteadi.com\r\n";
    
    mail($to, $subject, $message, $headers);
}
?> 