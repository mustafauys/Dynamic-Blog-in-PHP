
<?php

  $SQL = "SELECT
            yazi_id,
            baslik,
            yazildigi_tarih,
            yayinlanacagi_tarih,
            yazar_id,
            kategori_id,
            yazi_spotu,
            yazi,
            durum,
            begeni,
            sayac
          FROM yazilar
          WHERE yazi_id = '{$_GET["yaziid"]}'
          ORDER BY yazildigi_tarih DESC";

    // SQL komutunu MySQL veritabanı üzerinde çalıştır!
    $rows  = mysqli_query($db, $SQL);
    $row   = mysqli_fetch_assoc($rows);    // Makale içeriğinin ekrana yazdırılması

    $KayitSayisi = mysqli_num_rows($rows);
    if ($KayitSayisi == 0) {
      // Böyle bir makale yok! Ana sayfaya gönderelim
      header("location: index.php");
      die();
    }

    if( $_SESSION["giris_yapti"] <> 1) { // Site ziyaretçisi (Login olmuş yazar değil!)

        if ($row["durum"] == 0) { // Yazı BEKLEMEDE olarak ayarlanmış. Göstermeyelim.
          // Böyle bir makale yok! Ana sayfaya gönderelim
          header("location: index.php");
          die();
        }

        if ($row["yayinlanacagi_tarih"] > date("Y-m-d")) { // Yazı, ileri bir tarihte gösterilecek biçimde ayarlanmış.
          // Böyle bir makale yok! Ana sayfaya gönderelim
          header("location: index.php");
          die();
        }

    }

    // Sayfa gösterim sayacının arttırılmasını yapalım
    $SQL = "UPDATE yazilar SET sayac = sayac + 1 WHERE yazi_id = '{$_GET["yaziid"]}'";
    mysqli_query($db, $SQL);

  ?>
<script>
  // Sayfa başlığını Blog Adına göre düzenleyelim.
  document.title = "<?php echo $row["baslik"] . "::" . $GENEL_SiteAdi; ?>"; // Sayfa Başlığı
</script>

  <div class="container mt-4">
     <div class="row">
        <div class="col-md-12">

          <div class="alert alert-info" role="alert">

          <?php
            if( $_SESSION["giris_yapti"] == 1 AND ($_SESSION["yazar_id"] == $row["yazar_id"] OR $_SESSION["yetki_seviyesi"] == 2) ) {
              echo "<a href='?edityaziid={$row["yazi_id"]}'>Bu Makaleyi Düzenle</a>";
            }
          ?>


            <h1 class="display-5"><?php echo $row["baslik"];?></h1>
            <p class="lead"><?php echo $row["yazi_spotu"];?></p>
          </div>

        </div>
      </div>

      <div class="row">
       <div class="col-md-12">
              <img src="images/<?php echo $row["yazi_id"];?>.png" class="img-thumbnail m-1 rounded float-left" />
             <?php
                // Makaleler MarkDown ile yazılıyor.
                // Temel MarkDown'u kolayca render edebilmek için "Slimdown" kütüphanesi kullanıldı
                // Slimdown.php KAYNAK: https://gist.github.com/jbroadway/2836900
                require_once ('Slimdown.php');
                echo Slimdown::render ( $row["yazi"] );
                echo "<br><br><p>Bu yazı {$row["sayac"]} defa okunmuştur</p>";
             ?>
       </div> <!-- MakaleSonu -->
     </div> <!-- col -->
</div>
