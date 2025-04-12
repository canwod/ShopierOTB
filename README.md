# Otomatik Sipariş Bildirimi (OSB) Entegrasyonu

Bu proje, iyzico OSB (Otomatik Sipariş Bildirimi) sisteminin PHP entegrasyonunu içerir.

## Kurulum Adımları

### 1. Veritabanı Kurulumu

1. MySQL veritabanınıza bağlanın
2. `create_table.sql` dosyasındaki SQL komutunu çalıştırın
3. Bu komut `siparisler` adında yeni bir tablo oluşturacaktır

### 2. PHP Dosyası Ayarları

1. `osb_handler.php` dosyasını sunucunuza yükleyin
2. Dosyayı açın ve aşağıdaki veritabanı bilgilerini güncelleyin:
   ```php
   define('DB_HOST', 'localhost');     // Veritabanı sunucusu
   define('DB_USER', 'kullanici_adi'); // Veritabanı kullanıcı adı
   define('DB_PASS', 'sifre');         // Veritabanı şifresi
   define('DB_NAME', 'veritabani_adi'); // Veritabanı adı
   ```
3. E-posta ayarlarını güncelleyin:
   ```php
   $headers = "From: siparis@siteadi.com\r\n";
   ```

### 3. iyzico Panel Ayarları

1. iyzico paneline giriş yapın
2. "Otomatik Sipariş Bildirimi" bölümüne gidin
3. Bildirim URL'si olarak `osb_handler.php` dosyasının tam yolunu girin
   Örnek: `https://www.siteadiniz.com/osb_handler.php`
4. Protokol olarak `https://` seçilmesi önerilir
5. "Bildirim Testi" bölümünden test işlemi yapın

## Güvenlik Önlemleri

- Dosyayı public bir dizine yükleyin
- HTTPS kullanın
- Veritabanı bilgilerini güvenli tutun
- Error reporting'i production ortamında kapatın

## Hata Ayıklama

Sistem hataları `error_log` ile kaydedilir. Hataları kontrol etmek için:
- Linux: `/var/log/apache2/error.log`
- Windows: `xampp/apache/logs/error.log`

## OSB Yanıt Kodları

- `success`: İşlem başarılı
- `missing parameter`: Eksik parametre
- Boş yanıt: Hash doğrulama başarısız

## Önemli Notlar

1. Aynı sipariş bildirimi birden fazla kez gelebilir
2. Her bildirimde hash kontrolü yapılır
3. Sipariş numarası ile mükerrer kayıt kontrolü yapılır
4. Test siparişleri `test_mi` alanında belirtilir (0: canlı, 1: test)

https://www.youtube.com/watch?v=VqHoUsG7B8w

## Destek

Sorunlarınız için:
- Sistem loglarını kontrol edin
- iyzico teknik destek ile iletişime geçin

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. 
