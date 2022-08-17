<div class="halaman">
    <br>
    <form action="" method="POST">
        <input type="text" name="keyword" class="col-4" placeholder="Masukkan Nama Obat" autocomplete="off">
        <input type="hidden" name="id_apotek" value="<?php echo $id_apotek ?>"></intput>
        <button type="submit" name="cari" class="btn btn-outline-info"><b>Cari</b></button>
    </form>
    <br>
    <div class="card bg-light mb-1" style="width: 22rem;">
        <div class="card-body text-start border border-dark">
            <h6 class="my-2">Kategori :</h6>
            <p class="text-muted mb-1">1. K1 = Obat dengan rasio penjualan tinggi</p>
            <p class="text-muted mb-1">2. K2 = Obat dengan rasio penjualan rendah</p>
        </div>
    </div>
    <br>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nama</th>
                <th scope="col">Harga</th>
                <th scope="col">Stock</th>
                <th scope="col">Terjual</th>
                <th scope="col">Tersedia</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data_obat as $dataObat) :
            ?>
                <tr>
                    <th scope="row"><?php echo $dataObat["id"] ?></th>
                    <td><?php echo $dataObat["nama"]; ?></td>
                    <td><?php echo $dataObat["harga"]; ?></td>
                    <td><?php echo $dataObat["stock"]; ?></td>
                    <td><?php echo $dataObat["terjual"]; ?></td>
                    <td><?php echo $dataObat["tersedia"]; ?></td>
                </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" <?php if ($halamanAktif > 1) : ?>href="berhasil_login_user.php?page=data_obat_user&id_apotek=<?php echo $id_apotek; ?>&halaman=<?php echo $halamanAktif - 1; ?>" <?php endif ?>>Sebelumnya</a></li>
        <?php for ($i = 1; $i <= $banyakHalaman; $i++) : ?>
            <?php if ($i < 6) : ?>
                <li class="page-item">
                    <a class="page-link" href="berhasil_login_user.php?page=data_obat_user&id_apotek=<?php echo $id_apotek; ?>&halaman=<?php echo $halamanAktif + $i - 1; ?>"><?php echo $halamanAktif + $i - 1; ?></a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>
        <li class="page-item"><a class="page-link" <?php if ($halamanAktif < $banyakHalaman) : ?>href="berhasil_login_user.php?page=data_obat_user&id_apotek=<?php echo $id_apotek; ?>&halaman=<?php echo $halamanAktif + 1; ?>" <?php endif ?>>Selanjutnya</a></li>
    </ul>
</nav>
<div>
</div>