<?php
  if(isset($_POST["eposta"])) { // Form gönderilmiş.

    // NOT: Yazar YASAKLI ise giriş yapamaz.
    $SQL = "SELECT * FROM yazarlar
              WHERE
                  yazar_eposta  = '{$_POST["eposta"]}' AND
                  parola        = '{$_POST["parola"]}' AND
                  yazar_yasakli = 0
            ";

      // SQL komutunu MySQL veritabanı üzerinde çalıştır!
      $rows  = mysqli_query($db, $SQL);

      $KayitSayisi = mysqli_num_rows($rows);

      $GirisHatali = 0; // Varsayılan değer

      if ($KayitSayisi == 0) { // Giriş başarısız
        $GirisHatali = 99; // Giriş Hatalı
      }

      if ($KayitSayisi == 1) { // Giriş başarılı

        $row   = mysqli_fetch_assoc($rows);    // Yazar bilgilerini alalım

        // Session değişkenlerini dolduralım.
        $_SESSION["yazar_id"]       = $row["yazar_id"];
        $_SESSION["yazar_adi"]      = $row["yazar_adi"];
        $_SESSION["yetki_seviyesi"] = $row["yetki_seviyesi"];
        $_SESSION["yazar_yasakli"]  = $row["yazar_yasakli"];
        $_SESSION["giris_yapti"]    = 1;
        $GirisHatali = 0; // 0: Hata yok

        header("location: index.php"); // Giriş Başarılı ise ana sayfaya git
        die();
      }

  }
?>
<form method="post" autocomplete="off">

<div class="container">
  <div class="row">
      <div class="col-md-6 offset-md-3">
          <h3 class='m-5 text-center'>Yazar Girişi</h3>
          <?php
                if($GirisHatali == 99) {
                  echo "<p style='color:red;' class='text-center'>Kullanıcı Adı veya Parola Hatalı</p>";
                }
          ?>
          <div class="form-group">
              ePosta Adresiniz
              <input type="text" name="eposta" class="form-control" placeholder="ePosta Adresiniz">
          </div>
      </div>
      <div class="col-md-6 offset-md-3">
          <div class="form-group">
              Parolanız
              <input type="password" name="parola" class="form-control" placeholder="Parolanız">
          </div>
      </div>
      <div class="col-md-6 offset-md-3">
          <div class="form-group">
              <input type="submit" class="form-control btn btn-success" value="Giriş Yap">
          </div>
      </div>
  </div>
</div>

</form>
