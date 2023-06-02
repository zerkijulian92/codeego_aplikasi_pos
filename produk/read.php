<?php
//MEMANGGIL FUNGSI KONEKSI
require_once('../koneksi/koneksi.php');
// MENAMBAHKAN FUNGSI CARI
$cari = "-1";
if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $query = sprintf(
        "SELECT produk.*, namakategori FROM produk
    LEFT JOIN kategori ON idkategori = kategori
    WHERE namaproduk LIKE %s
    ORDER BY idproduk ASC",
        inj($koneksi, "%" . $cari . "%", "text")
    );
} else {
    $query = "SELECT produk.*, namakategori FROM produk 
LEFT JOIN kategori ON idkategori = kategori
ORDER BY idproduk ASC";
}

//MENAMPILKAN DATA
//note: Menggunakan LEFT JOIN untuk memanggil field namakategori di tabel kategori

$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);
?>

<h3>DAFTAR PRODUK</h3>
<a href="insert.php">Add Produk</a>
<p></p>
<form action="" method="get">
    Cari Data : <input type="text" name="cari">
    <button type="sumbit">Search</button>
</form>

<!-- MENAMBAHKAN FUNGSI KETIKA DATA TIDAK TERSEDIA(KOSONG) -->
<?php if ($totalRows > 0) { ?>
    <table border="1">
        <tr>
            <td>NO.</td>
            <td>KODE</td>
            <td>NAMA PRODUK</td>
            <td>KATEGORI</td>
            <td>HARGA DASAR</td>
            <td>HARGA JUAL</td>
            <td>STOK</td>
            <td>ACTION</td>
        </tr>
        <?php $nomor = 1;
        do { ?>
            <tr>
                <td><?php echo $nomor; ?></td>
                <td><?php echo $row['kodeproduk']; ?></td>
                <td><?php echo $row['namaproduk']; ?></td>
                <td><?php echo $row['namakategori']; ?></td>
                <td><?php echo $row['hargadasar']; ?></td>
                <td><?php echo $row['hargajual']; ?></td>
                <td><?php echo $row['stok']; ?></td>
                <td><a href="update.php?id=<?php echo $row['idproduk']; ?>">Edit</a> | <a href="delete.php?id=<?php echo $row['idproduk']; ?>">Hapus</a></td>
            </tr>
        <?php $nomor++;
        } while ($row = mysqli_fetch_assoc($eksekusi)); ?>
    </table>
<?php } else {
    echo "Data tidak tersedia";
}
?>