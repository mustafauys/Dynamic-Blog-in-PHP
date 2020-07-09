<?php

if( $_SESSION["giris_yapti"] <> 1 ) {
  // Kullanıcı giriş yapmamış !
  // Ana sayfaya yönlendirelim
  header("location index.php");
  die();
}

if( $_SESSION["yetki_seviyesi"] <> 2 ) {
  // Kullanıcı giriş yapmış, ancak yetkili değil!
  // Ana sayfaya yönlendirelim
  header("location index.php");
  die();
}


  if( isset($_POST["yeni_yazar_id"]) ) { // Form gönderilmişse başlayalım

    // echo "<pre>";  print_r($_POST);  echo "</pre>"; die();

    ##########################
    ########################## TOPLU VERİ GÜNCELLEME
    ##########################

    // form elementinin adı [] ile bitiyorsa bu bir dizi değişkenidir
    //
    // TOPLU VERİ GÜNCELLEME ADIMLARI
    // foreach ile ID dizisi üzerinde dönülür.
    // Döngünün $KEY'i esas alınarak diğer dizilerden veriler alınır
    // SQL oluşturulur ve çalıştırılır

    // Form değişkenlerini bir dizi değişkenine atadık
    $arrYazar_id       = $_POST["yazar_id"];
    $arrYazar_adi      = $_POST["yazar_adi"];
    $arrYazar_eposta   = $_POST["yazar_eposta"];
    $arrYetki_seviyesi = $_POST["yetki_seviyesi"];
    $arryazar_yasakli  = $_POST["yazar_yasakli"];
    $arrParola         = $_POST["parola"];
    $arrSiralama       = $_POST["siralama"];

    foreach ($arrYazar_id as $key => $value) {
      $SQL = sprintf("UPDATE yazarlar
              SET
                  yazar_adi      = '%s',
                  yazar_eposta   = '%s',
                  yetki_seviyesi = '%s',
                  yazar_yasakli  = '%s',
                  parola         = '%s',
                  siralama       = '%s'
              WHERE
                  yazar_id = '%s'   ",
              $arrYazar_adi[$key],
              $arrYazar_eposta[$key],
              $arrYetki_seviyesi[$key],
              $arryazar_yasakli[$key],
              $arrParola[$key],
              $arrSiralama[$key],
              $arrYazar_id[$key]
            );

        $rows = mysqli_query($db, $SQL);

    } //foreach

    // Buraya geldiğimizde, tüm kayıtlar güncellenmiştir.


    ##########################
    ########################## TOPLU VERİ EKLEME
    ##########################

    // form elementinin adı [] ile bitiyorsa bu bir dizi değişkenidir
    //
    // TOPLU VERİ EKLEME ADIMLARI
    // foreach ile yeni_ID dizisi üzerinde dönülür.
    // Döngünün $KEY'i esas alınarak diğer dizilerden veriler alınır
    // SQL oluşturulur ve çalıştırılır

    // Form değişkenlerini bir dizi değişkenine atadık
    $arrYazar_id       = $_POST["yeni_yazar_id"];
    $arrYazar_adi      = $_POST["yeni_yazar_adi"];
    $arrYazar_eposta   = $_POST["yeni_yazar_eposta"];
    $arrYetki_seviyesi = $_POST["yeni_yetki_seviyesi"];
    $arrYazar_yasakli = $_POST["yeni_yazar_yasakli"];
    $arrParola         = $_POST["yeni_parola"];
    $arrSiralama       = $_POST["yeni_siralama"];

    foreach ($arrYazar_id as $key => $value) {
      $SQL = sprintf("INSERT INTO yazarlar
              SET
                  yazar_adi      = '%s',
                  yazar_eposta   = '%s',
                  yetki_seviyesi = '%s',
                  yazar_yasakli  = '%s',
                  parola         = '%s',
                  siralama       = '%s'    ",
              $arrYazar_adi[$key],
              $arrYazar_eposta[$key],
              $arrYetki_seviyesi[$key],
              $arrYazar_yasakli[$key],
              $arrParola[$key],
              $arrSiralama[$key]
            );

        // Eğer, yeni sahaların yazar adı DOLUYSA yeni kaydı ekleyelim...
        if( trim($arrYazar_adi[$key]) <> "") { // trim: boşlukları temizler
          $rows = mysqli_query($db, $SQL);
        }

    } //foreach

    // Buraya geldiğimizde, tüm kayıtlar güncellenmiştir.


  } // Form gönderilmişse başlayalım

?>

<form method="post" autocomplete="off">

<div class="container">
  <div class="row">
      <div class="col-md-8 offset-md-2">
          <h3 class='m-5 text-center'>Yazar Yönetimi</h3>
      </div>

      <div class="col-md-10 offset-md-1">





        <form method='POST' autocomplete="off">

        <?php

        $SQL   = "SELECT * FROM yazarlar ORDER BY siralama";
        $rows  = mysqli_query($db, $SQL);

        $TabloBasi = "
        <table border=1 cellspacing=0 cellpadding=5>
        <tr>
          <td>YazarNo</td>
          <td>Yazar Adı</td>
          <td>ePosta</td>
          <td>Parola</td>
          <td>Yetki Seviyesi</td>
          <td>Yasaklı</td>
          <td>Sıralama</td>
        </tr>
        ";
        $TabloSonu = "</table>";

        echo $TabloBasi;

        $c = 0; // SıraNo yazdırabilmek için...

        while($row = mysqli_fetch_assoc($rows)) {
            $c++; //Sıra numarasını arttır.

            extract($row); // İlişkilendirilmiş dizinin elemanları için değişken oluşturur
            // Bundan böyle, $row["yazar_adi"] yazmak yerine $yazar_adi yazılabilecek

            echo sprintf("
                  <tr>
                    <td>%s<input type='hidden' name='yazar_id[]'       value='%s' /></td>
                    <td><input type='text'     name='yazar_adi[]'      value='%s' /></td>
                    <td><input type='text'     name='yazar_eposta[]'   value='%s' /></td>
                    <td><input type='password' name='parola[]'         value='%s' style='width:100px;'/></td>
                    <td><input type='text'     name='yetki_seviyesi[]' value='%s' style='width:140px;'/></td>
                    <td><input type='text'     name='yazar_yasakli[]'  value='%s' style='width:140px;'/></td>
                    <td><input type='text'     name='siralama[]'       value='%s' style='width:100px;'/></td>
                  </tr>",
                  $c,
                  $yazar_id,
                  $yazar_adi,
                  $yazar_eposta,
                  $parola,
                  $yetki_seviyesi,
                  $yazar_yasakli,
                  $siralama
                );

        } // while sonu

        // 3 Adet YENİ satırı ekler
        for($i=1; $i<=3; $i++) {
          echo "
              <tr>
                <td>YENİ<input type='hidden' name='yeni_yazar_id[]'></td>
                <td><input type='text'     name='yeni_yazar_adi[]'      placeholder='Yazar adını giriniz'/></td>
                <td><input type='text'     name='yeni_yazar_eposta[]'   placeholder='ePosta giriniz'/></td>
                <td><input type='password' name='yeni_yazar_parola[]'   placeholder='Parola'            style='width:100px;'/></td>
                <td><input type='text'     name='yeni_yetki_seviyesi[]' placeholder='1:Yazar 2:Admin'   style='width:140px;'/></td>
                <td><input type='text'     name='yeni_yazar_yasakli[]'  placeholder='1:Evet 0:Hayır'    style='width:140px;'/></td>
                <td><input type='text'     name='yeni_siralama[]'       placeholder='Sıralama'          style='width:100px;'/></td>
              </tr>";
        }

        echo $TabloSonu;


        mysqli_close($db);

        ?>

          <input type='submit' class="btn btn-success mt-3" value='Verileri Kaydet !'>
        </form>









      </div> <!-- Yazar Listeleme Tablosu-->

  </div>
</div>

</form>
