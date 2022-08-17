<div class="halaman">
    <br>
    <button type="button" class="btn btn-outline-success mb-4" data-bs-toggle="modal" data-bs-target="#modalTambahObat">
        <b>Tambah Data</b>
    </button>
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
                <th scope="col" colspan="3">Keterangan</th>
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
                    <td>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditObat<?= $dataObat['id']; ?>"><b>Edit</b></button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapusObat<?= $dataObat['id']; ?>"><b>Hapus</b></button>
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>

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

    <!-- Create -->
    <div class="modal fade" id="modalTambahObat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $dataObat['id']; ?>">
                        <input type="hidden" name="id_apotek" value="<?= $dataObat['id_apotek']; ?>">

                        <div class="mb-3">
                            <label for="nama"><b>Nama Obat</b></label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga"><b>Harga</b></label>
                            <input type="text" name="harga" id="harga" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="stock"><b>Stock</b></label>
                            <input type="text" name="stock" id="stock" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="terjual"><b>Terjual</b></label>
                            <input type="text" name="terjual" id="terjual" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="tersedia"><b>Tersedia</b></label>
                            <input type="text" name="tersedia" id="tersedia" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="tambahobat" class="btn btn-outline-success"><b>Simpan</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit -->
    <?php foreach ($data_obat as $dataObat) : ?>
        <div class="modal fade" id="modalEditObat<?= $dataObat['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= $dataObat['id']; ?>">
                            <input type="hidden" name="id_apotek" value="<?= $dataObat['id_apotek']; ?>">

                            <div class="mb-3">
                                <label for="nama"><b>Nama Obat</b></label>
                                <input type="text" name="nama" id="nama" class="form-control" value="<?= $dataObat['nama']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="harga"><b>Harga</b></label>
                                <input type="text" name="harga" id="harga" class="form-control" value="<?= $dataObat['harga']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="stock"><b>Stock</b></label>
                                <input type="text" name="stock" id="stock" class="form-control" value="<?= $dataObat['stock']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="terjual"><b>Terjual</b></label>
                                <input type="text" name="terjual" id="terjual" class="form-control" value="<?= $dataObat['terjual']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="tersedia"><b>Tersedia</b></label>
                                <input type="text" name="tersedia" id="tersedia" class="form-control" value="<?= $dataObat['tersedia']; ?>" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="ubahobat" class="btn btn-outline-warning"><b>Ubah</b></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Hapus -->
    <?php foreach ($data_obat as $dataObat) : ?>
        <div class="modal fade" id="modalHapusObat<?= $dataObat['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Anda Ingin Menghapus Data <?= $dataObat['nama']; ?> ?</p>
                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= $dataObat['id']; ?>">
                            <input type="hidden" name="id_apotek" value="<?= $dataObat['id_apotek']; ?>">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="hapusobat" class="btn btn-outline-danger"><b>Hapus</b></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>