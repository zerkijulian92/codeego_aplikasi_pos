<?php

// menghubungkan ke file koneksi.php
require_once('koneksi/koneksi.php');
// MENAMBAHKAN FUNGSI CARI
$cari = "-1";
if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $query = sprintf(
        "SELECT  * FROM kassa WHERE userkassa LIKE %s OR fullname LIKE %s ORDER BY idkassa ASC",
        inj($koneksi, "%" . $cari . "%", "text"),
        inj($koneksi, "%" . $cari . "%", "text")
    );
} else {
    $query = "SELECT  * FROM kassa ORDER BY idkassa ASC";
}
//---

$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);

?>

<h3>DAFTAR KASA (KASIR)</h3>
<a href="?page=kassa/insert">Tambah Kasir</a>
<p></p>
<form action="" method="get">
    Cari Data : <input type="text" name="cari">
    <button type="submit">Search</button>
    <input type="hidden" name="page" value="kassa/read">
</form>

<!-- MENAMBAHKAN FUNGSI KETIKA DATA TIDAK TERSEDIA(KOSONG) -->
<?php if ($totalRows > 0) { ?>
    <table border="1">
        <tr>
            <td>NO</td>
            <td>USERNAME</td>
            <td>NAMA LENGKAP</td>
            <td>ACTION</td>
        </tr>
        <?php $no = 1;
        do {  ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['userkassa']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><a href="?page=kassa/update&id=<?php echo $row['idkassa']; ?>">Edit</a> | <a href="?page=kassa/delete&id=<?php echo $row['idkassa']; ?>">Hapus</a></td>
            </tr>
        <?php $no++;
        } while ($row = mysqli_fetch_assoc($eksekusi)) ?>
    </table>
<?php } else {
    echo "Data tidak tersedia";
}
?>