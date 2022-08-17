<div class="halaman">
    <br>
    <button type="button" class="btn btn-outline-success mb-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <b>Tambah Data</b>
    </button>
    <table class="table">
        <thead class="table-dark text-center">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nama</th>
                <th scope="col">Alamat</th>
                <th scope="col">Data Obat</th>
                <th scope="col" colspan="3">Keterangan</th>
            </tr>
        </thead>

        <body>
            <?php
            $number = 1;
            foreach ($data_apotek as $dataApotek) :
            ?>
                <tr>
                    <th scope="row"><?php echo $number; ?></th>
                    <td><?php echo $dataApotek["nama"]; ?></td>
                    <td><?php echo $dataApotek["alamat"]; ?></td>
                    <td><a href="berhasil_login.php?page=data_obat&id_apotek=<?php echo $dataApotek["id"]; ?>" class="btn btn-outline-info" role="button" aria-disabled="true"><b>Data Obat</b></a></td>
                    <td>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $dataApotek['id']; ?>"><b>Edit</b></button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $dataApotek['id']; ?>"><b>Hapus</b></button>
                    </td>
                </tr>
            <?php
                $number += 1;
            endforeach;
            ?>
            </tbody>
    </table>
</div>

<!-- Create -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Apotek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $dataApotek['id']; ?>">

                    <div class="mb-3">
                        <label for="nama"><b>Nama Apotek</b></label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat"><b>Alamat</b></label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="tambah" class="btn btn-outline-success"><b>Simpan</b></button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit -->
<?php foreach ($data_apotek as $dataApotek) : ?>
    <div class="modal fade" id="modalEdit<?= $dataApotek['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data Apotek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $dataApotek['id']; ?>">
                        <div class="mb-3">
                            <label for="nama"><b>Nama Apotek</b></label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= $dataApotek['nama']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat"><b>Alamat</b></label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $dataApotek['alamat']; ?>" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="ubah" class="btn btn-outline-warning"><b>Ubah</b></button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Hapus -->
<?php foreach ($data_apotek as $dataApotek) : ?>
    <div class="modal fade" id="modalHapus<?= $dataApotek['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Apotek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda Ingin Menghapus Data <?= $dataApotek['nama']; ?> ?</p>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $dataApotek['id']; ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="hapus" class="btn btn-outline-danger"><b>Hapus</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>