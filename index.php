<?php
  error_reporting(0); //bu kod sayesinde sayfadaki isset ve empty komutları bir sorgu komutu olduğu için hata mesajı olarak ekrana yazdırmayı engeller.
  @session_start();

  if( isset($_GET["oturumukapat"]) ) {
    session_destroy(); // Oturum kapandı
    header("location: index.php");
    die();
  }

  require("ayarlar.php"); // Site genelini ilgilendiren ayarlara ilişkin değişkenler

  require("db.php"); // Veritabanına bağlantı kuralım

  require("sayfa.ust.php"); // Sayfanın üst kısmını oluşturan HTML kodlar

  require("sayfa.navbar.php");  // Sayfanın Navigasyon Barını oluşturan HTML kodlar

  $SayfaGosterildi = 0;

  if(isset($_GET["yaziid"])) { // Tek yazı göster
    require("blog.goster.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["yazargirisi"])) { // Yazar girişi yap
    require("yazar.girisi.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["yazaryonetimi"])) { // Yazar Yönetimi
    require("yazar.yonetimi.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["yeniyaziekle"])) { // Yeni yazı ekleme ekranı
    require("yeni.yazi.ekle.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["edityaziid"])) { // Yazı düzenleme ekranı
    require("yeni.yazi.duzenle.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["yazar"])) { // Yazara göre süzerek yazıları lisele
    require("blog.liste.php");
    $SayfaGosterildi=1;
  }

  if(isset($_GET["kategori"])) {  // Kategoriyegöre süzerek yazıları lisele
    require("blog.liste.php");
    $SayfaGosterildi=1;
  }


  if( $SayfaGosterildi == 0 ) { // Hiç parametre yoksa Jumbotron gösterelim
    require("sayfa.jumbotron.php");
    require("blog.liste.php");
  }


  require("sayfa.alt.php");  // Sayfanın alt kısmını oluşturan HTML kodlar

  // print_r($_SESSION); // Session değişkenlerinin içini görelim...

?>
