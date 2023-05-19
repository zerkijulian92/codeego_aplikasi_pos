<?php

// menghubungkan ke file koneksi.php
require_once('../koneksi/koneksi.php');

$query = "SELECT  * FROM kassa ORDER BY idkassa ASC";
$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);

?>

<h3>DAFTAR KASA (KASIR)</h3>
<a href="insert.php">Tambah Kasir</a>
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
            <td><a href="update.php">Edit</a> | <a href="delete.php?id=<?php echo $row['idkassa']; ?>">Hapus</a></td>
        </tr>
    <?php $no++;
    } while ($row = mysqli_fetch_assoc($eksekusi)) ?>
</table>