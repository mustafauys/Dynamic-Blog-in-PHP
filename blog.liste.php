<div class="container mt-4">
   <div class="row">


<?php

################## Süzmeye göre başlık hazırlayalım
################## Süzmeye göre başlık hazırlayalım
  $SAYFA_BASLIGI = "";
  if(isset($_GET["kategori"])) {
    // Listele sayfası bağlığı için Kategori adını çekelim ki
    $SQL = "SELECT * FROM kategoriler WHERE kategori_id = '{$_GET["kategori"]}' ";
    // SQL komutunu MySQL veritabanı üzerinde çalıştır!
    $rows  = mysqli_query($db, $SQL);
    // Gelen satırı okuyalım
    $row = mysqli_fetch_assoc($rows);
    // Kategori adını değişkenimize alalım
    $SAYFA_BASLIGI = "Kategori : " . $row["kategori_adi"];
  }

  if(isset($_GET["yazar"])) {
    // Listele sayfası bağlığı için Kategori adını çekelim ki
    $SQL = "SELECT * FROM yazarlar WHERE yazar_id = '{$_GET["yazar"]}' ";
    // SQL komutunu MySQL veritabanı üzerinde çalıştır!
    $rows  = mysqli_query($db, $SQL);
    // Gelen satırı okuyalım
    $row = mysqli_fetch_assoc($rows);
    // Yazar adını değişkenimize alalım
    $SAYFA_BASLIGI = "Yazar : " . $row["yazar_adi"];
  }

  if($SAYFA_BASLIGI <> "") {
    echo "
      <div class='col-md-12'>
        <div class='alert alert-success' role='alert'>
            <h1>$SAYFA_BASLIGI</h1>
        </div>
      </div>       ";
  }



################## SQL'in KOŞUL BÖLÜMÜNÜ HAZIRLAYALIM
################## SQL'in KOŞUL BÖLÜMÜNÜ HAZIRLAYALIM

  $KOSUL = array();

  $KOSUL[] = "1";

  if( isset($_GET["yazar"]) ) { // Yazar adına göre süz
    $KOSUL[] = " yazar_id = '{$_GET["yazar"]}' ";
  }

  if( isset($_GET["kategori"]) ) { // Kategoriye göre süz
    $KOSUL[] = " kategori_id = '{$_GET["kategori"]}' ";
  }

  if( $_SESSION["giris_yapti"] == 1 ) {
    // Login olanlara için uygulanacak ilave kriterler
    //
    //
  } else {
    // Yazar login olmamışsa yayinlanacagi_tarih kriteri de çalışmalı
    $BUGUN = date("Y-m-d");
    $KOSUL[] = " yayinlanacagi_tarih <= '$BUGUN' ";

    // Yazar login olmamışsa Yazı Durumu BEKLEMEDE olanları da listele
    $KOSUL[] = " durum = 1 ";

  }

  $SQL_KOSULU = "";
  if(count($KOSUL) > 0) {
    $SQL_KOSULU = implode(" AND ", $KOSUL);
  }

  $SQL = "SELECT
            yazi_id,
            baslik,
            yazildigi_tarih,
            yayinlanacagi_tarih,
            yazar_id,
            kategori_id,
            yazi_spotu,
          --  yazi,
            durum,
            begeni,
            sayac
          FROM yazilar
          WHERE
            $SQL_KOSULU
          ORDER BY yazildigi_tarih DESC";

    // SQL komutunu MySQL veritabanı üzerinde çalıştır!
    $rows  = mysqli_query($db, $SQL);

    while($row = mysqli_fetch_assoc($rows)) { // Kayıt adedince döner
        // Makale içeriğinin ekrana yazdırılması   ?>

     <div class="col-md-3">
       <div class="card mb-4 shadow-sm">
         <img class="card-img-top" src="images/<?php echo $row["yazi_id"];?>.png" height="250" width="100%"  />
         <div class="card-body">
           <p class="card-text">
             <b> <?php echo substr($row["yazi_spotu"],0,20);?> ...</b><br />
            
           </p>
           <div class="d-flex justify-content-between align-items-center">
             <div class="btn-group">
               <a class="btn btn-sm btn-outline-success" href="index.php?yaziid=<?php echo $row["yazi_id"];?>">Devamını Oku...</a>

               <?php
                 if( $_SESSION["giris_yapti"] == 1 AND ($_SESSION["yazar_id"] == $row["yazar_id"] OR $_SESSION["yetki_seviyesi"] == 2) ) {
                   echo "
                   <a class='btn btn-sm btn-outline-info' href='?edityaziid={$row["yazi_id"]}'>Düzenle</a>
                   ";
                 }
               ?>


             </div>
          <!--   <small class="text-muted">9 mins</small>  -->
           </div>
         </div>
       </div> <!-- MakaleSonu -->
     </div> <!-- col -->

   <?php } // while ?>


   </div> <!-- row -->
 </div> <!-- container -->
