### Hepsipay Magento Module


###### Aşağıdaki adımları takip ederek başarılı bir şekilde Magento entegrasyonunu tamamlayabilirsiniz..

###### Desteklediğimiz Magento versiyonları: 1.9.X - 2.X


#### Kurulum

***Bu dökümantasyon Magento V.1.9.x versiyonları için geçerlidir.***

###### 1.İlk olarak FTP bağlantısı ile websitemizin ana dizinine giriyoruz. Hepsipay eklentimizi .zip uzantılı dosyasından çıkartıp web sitemizin anadizinine yüklüyoruz.

![Alt text](https://github.com/Hepsipay/Magento/blob/master/Magento%20Setup%20Images/Magento1.PNG?raw=true "Magento files 1")

###### 2.Magento admin panelimize giriyoruz. System sekmesinin altındaki Configuration butonuna tıklıyoruz.

![Alt text](https://github.com/Hepsipay/Magento/blob/master/Magento%20Setup%20Images/Magento2.PNG?raw=true "Magento files 2")

###### 3.Açılan sayfadan Payment Methods butonuna tıklıyoruz.

![Alt text](https://github.com/Hepsipay/Magento/blob/master/Magento%20Setup%20Images/Magento3.PNG?raw=true "Magento files 3")

###### 4.Hepsipay Payment Gateway bölümünde ilgili alanları doldurup Save Config butonuna tıklıyoruz. Ve hepsipay eklentimiz hazır.

![Alt text](https://github.com/Hepsipay/Magento/blob/master/Magento%20Setup%20Images/Magento4.PNG?raw=true "Magento files 4")

#### Magento Admin Panel Ayarları

![Alt text](https://github.com/Hepsipay/Magento/blob/master/Magento%20Setup%20Images/Magento5.PNG?raw=true "Magento files 5")

* Server IP kısmı websitenizin bulunduğu sunucunun IP bilgisini gösterir. Bilgilendirme amaçlıdır. Değiştirilebilme özelliği yoktur.
* Title kısmı ödeme seçenekleri arasında plugininizin isimlendirilmesi ile ilgilidir. “KREDİ KARTI İLE ÖDE” gibi değiştirilip özelleştirilebilir.
* Enabled bu kısım plugini aktif eden kısımdır. “Yes” olduğu taktirde ödeme seçenekleri arasında hepsipay plugini görebilirsiniz. “Hayır” ise hepsipay plugini ödeme metodları arasında görünmez.
* End Point kısmı hepsipay ile yapılan bağlantılar için ihtiyaç duyulan servis URL’idir. Değişiklik yapılamaz. Bilgilendirme için gösterilmektedir.
* Apikey ve SecretKey kısımları size özel olan değerlerdir. Şifreleme için kullanılmaktadır. Plugin yüklemesi yapıldıktan sonra bu kısımları hepsipay tarafından size paylaşılan değerler ile doldurmanız gerekmektedir.
* Enable Installment kısmı evet seçildiği takdirde müşterileriniz kredi kartlarının ilk 6 hanelerini girdiği anda pluginimiz kredi kartının sahip olduğu tüm taksit seçeneklerini müşterilerinize sunar.
* Enable Commission kısmı taksitler için komisyon almanızı sağlar. Ancak tek çekimlerde komisyon eklenmez. Sadece taksitler için geçerlidir.
* Force 3D secure for DEBIT card kısmı zorunlu olarak evet seçilidir. Değiştirilemez. Debit kartlarda 3D hep zorunlu tutulmak zorundadır.
* Enable 3D secure kısmı kredi kartları ile yapılan ödemelerde müşterilerinizin 3D kullanabilmeleri için hazırlanmıştır. Evet seçildiği takdirde müşterileriniz ödeme yaparken 3D Güvenlik protokolünü kullanıp kullanmama seçeneğine sahip olurlar. Hayır seçildiği takdirde müşterileriniz 3D güvenlik prosedürünü kullanamazlar.
* Force 3D secure kısmı 3D güvenlik prosedürünü her müşteri için zorunlu tutabilirsiniz. Evet seçildiği takdirde bütün işlemlerde 3D zorunlu olarak kullanılır. Eğer hepsipay tarafından 3D Güvenlik protokolü tarafınıza zorunlu tutulmuşsa bu seçeneği aktfif etmek zorundasınız. Aksi halde “Hash hatası ....” uyarısı ile karşılaşacaksınız.

#### Magento Hepsipay Hata Mesajları Ve Çözümleri

* Geçersiz kimlik bilgileri: Ödeme sayfasında kart bilgileri girilip “Ödeme yap” butonu tıklanıldıktan sonra bu hata ile karşılaşılıyorsa Admin panele gidip ApiKey ve SecretKey değerleriniz yanlış demektir. Sizinle paylaşılan değerleri dikkatlice tekrar kontrol ediniz.
* Üye işyeri bulunamadı: Bu hata ile karşılasılırsa öncelikli olarak ApiKey ve SecretKey değerlerinizin doğru olduğundan emin olunuz. Değerlerin sizin ile paylaşılan değerler ile aynı olduğundan emin iseniz hepsipay üye işyeri listesinde kaydınız yok demektir. Durumla ilgili bizimle iletişime geçiniz.
* Server IP tanınmıyor: Bu uyarı pluginimizi kullanmış olduğunuz websitesinin bulunmuş olduğu sunucunun çıkış IP bilgisi hepsipay tarafında kayıtlı olmadığı anlamına gelir. Öncelikle server IP değerinin değişmesine neden olacak hareketler sonrası pluginin çalışabilmesi için hepsipay’i bilgilendirneiz gerekmektedir. Hosting değişikliği gibi.
* Para birimi desteklenmiyor: Bu uyarı websiteinizde kullanmış olduğunuz para biriminin destenlenmediğini gösterir.
* Hash hatası: ApiSecret değerinizin hatalı olduğunu gösterir. ApiKey ve Secret değerlerinizin doğruluğundan emin olduğunuz halde aynı uyarı mesajı ile karşılaşırsanız bizimle itletişime geçiniz.
