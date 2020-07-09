<?php

  if( $_SESSION["giris_yapti"] <> 1 ) {
    // Kullanıcı giriş yapmamış !
    // Ana sayfaya yönlendirelim
    header("location index.php");
    die();
  }

  if( isset($_POST["baslik"]) ) { // Yeni yazı formu gömnderilmiş. Kaydedelim...

      // Eksik veri girişi kontrolü
      if($_POST["baslik"] == "" OR
         $_POST["yazildigi_tarih"] == "" OR
         $_POST["yayinlanacagi_tarih"] == "" OR
         $_POST["kategori_id"] == "" OR
         $_POST["yazi_spotu"] == "" OR
         $_POST["yazi"] == "" OR
         $_POST["durum"] == ""  ) {
            $EksikVeriVar = 1;
          } else {
            $EksikVeriVar = 0;
          }

    // Blog yazısını eklemek için gerekli SQL'i hazırlayalım
    $SQL = sprintf("INSERT INTO
                yazilar
            SET
                baslik = '%s',
                yazildigi_tarih = '%s',
                yayinlanacagi_tarih = '%s',
                yazar_id = '%s',
                kategori_id = '%s',
                yazi_spotu = '%s',
                yazi = '%s',
                durum = '%s'   ",
            $_POST["baslik"],
            $_POST["yazildigi_tarih"],
            $_POST["yayinlanacagi_tarih"],
            $_SESSION["yazar_id"],
            $_POST["kategori_id"],
            $_POST["yazi_spotu"],
            $_POST["yazi"],
            $_POST["durum"]    );

          if( $EksikVeriVar == 0) { // Eksik veri bulunmuyor.
            // SQL komutunu MySQL veritabanı üzerinde çalıştır!
            $rows  = mysqli_query($db, $SQL);

            // Bize, yeni makalenin ID bilgisi gerekiyor...
            $YeniMakaleID = mysqli_insert_id($db);

            // Yeni eklenen makale sayfasına geçiş yap
            header("location: index.php?yaziid=$YeniMakaleID");
            die();
          }


  }



  // Kategori adlarını alalım...
  $SQL = "SELECT kategori_id, kategori_adi FROM kategoriler ORDER BY siralama";

  // SQL komutunu MySQL veritabanı üzerinde çalıştır!
  $rows  = mysqli_query($db, $SQL);

  // Linkleri hazırlayalım
  $Kategoriler = "";
  while($row = mysqli_fetch_assoc($rows)) { // Kayıt adedince döner
      $Kategoriler .= "<option value='{$row["kategori_id"]}'>{$row["kategori_adi"]}</option>";
  }


?>
<div class="container">

  <div class="row">

    <div class="col-xl-8 offset-xl-2 py-5">

      <h1 class="text-center">Blog Yazısı Ekle</h1>

<?php if($EksikVeriVar == 1) { ?>
      <div id="MesajSonucAlani" class="alert alert-success" role="alert">
          Eksik veriler var! Tamamlayınız...
      </div>
<?php } ?>

      <form method="post" autocomplete="off" >

          <div class="row">

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yazı Başlığı
                      <input type="text" name="baslik" value="<?php echo $_POST["baslik"];?>" class="form-control" >
                  </div>
              </div>

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yazı Spotu (Özet)
                      <input type="text" name="yazi_spotu" value="<?php echo $_POST["yazi_spotu"];?>" class="form-control" >
                  </div>
              </div>

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yazı Kategorisi
                      <select name="kategori_id" class="form-control" >
                          <option value="">*** SEÇİNİZ ***</option>
                          <?php echo $Kategoriler; ?>
                      </select>
                  </div>
              </div>

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yazıldığı Tarih
                      <input type="text" name="yazildigi_tarih" value="<?php echo $_POST["yazildigi_tarih"];?>" class="form-control" value="<?php echo date("Y-m-d"); ?>" >
                  </div>
              </div>

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yayınlanacağı Tarih
                      <input type="text" name="yayinlanacagi_tarih" value="<?php echo $_POST["yayinlanacagi_tarih"];?>" class="form-control" value="<?php echo date("Y-m-d"); ?>" >
                  </div>
              </div>

              <div class="col-md-10 offset-md-1">
                  <div class="form-group">
                      Yazının Yayın Durumu
                      <select name="durum" class="form-control" >
                          <option value="1">Yayında</option>
                          <option value="0">Beklemede</option>
                      </select>
                  </div>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      Blog Yazınızın İçeriği
                      <textarea name="yazi" class="form-control" rows="10" ><?php echo $_POST["yazi"];?></textarea>
                  </div>
              </div>
              <div class="col-md-12">
                  <input type="submit" class="btn btn-success btn-send" value="Blog Yazısını Kaydet !">
              </div>
          </div>


      </form>

    </div> <!-- /col-8 -->

  </div> <!-- /row -->

</div> <!-- /container -->
