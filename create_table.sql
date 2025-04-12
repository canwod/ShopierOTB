-- OSB (Otomatik Sipariş Bildirimi) için veritabanı tablosu
CREATE TABLE siparisler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siparis_no VARCHAR(50) UNIQUE,
    email VARCHAR(255),
    para_birimi TINYINT,
    fiyat DECIMAL(10,2),
    musteri_adi VARCHAR(100),
    musteri_soyadi VARCHAR(100),
    urun_sayisi INT,
    urun_id VARCHAR(50),
    urun_listesi TEXT,
    sepet_detaylari TEXT,
    musteri_notu TEXT,
    test_mi TINYINT(1),
    kayit_tarihi DATETIME,
    INDEX (siparis_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 