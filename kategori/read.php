<?php
// menghubungkan ke file koneksi.php
require_once('../koneksi/koneksi.php');

$query = "SELECT  * FROM kategori ORDER BY idkategori ASC";
$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);

?>

<h3>DAFTAR KATEGORI</h3>
<a href="insert.php">Kategori</a>
<p></p>
<form action="" method="get">
    Cari Data : <input type="text" name="cari">
    <button type="submit">Search</button>
</form>

<!-- MENAMBAHKAN FUNGSI KETIKA DATA TIDAK TERSEDIA(KOSONG) -->
<?php if ($totalRows > 0) { ?>

    <table border="1">
        <tr>
            <td>NO.</td>
            <td>NAMA KATEGORI</td>
            <td>KET</td>
            <td>AKSI</td>
        </tr>

        <?php $no = 1;
        do {  ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['namakategori']; ?></td>
                <td><?php echo $row['ketkategori']; ?></td>
                <td><a href="update.php?id=<?php echo $row['idkategori']; ?>"> Edit</a> | <a href="delete.php?id=<?php echo $row['idkategori']; ?>">Hapus</td>
            </tr>
        <?php
            $no++;
        } while ($row = mysqli_fetch_assoc($eksekusi)); ?>
    </table>
<?php } else {
    echo "Data tidak tersedia";
}
?>