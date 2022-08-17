<div class="halaman">
    <br>
    <table class="table">
        <thead class="table-dark text-center">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nama</th>
                <th scope="col">Alamat</th>
                <th scope="col">Data Obat</th>
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
                    <td><a href="berhasil_login_user.php?page=data_obat_user&id_apotek=<?php echo $dataApotek["id"]; ?>" class="btn btn-outline-info" role="button" aria-disabled="true"><b>Data Obat</b></a></td>
                </tr>
            <?php
                $number += 1;
            endforeach;
            ?>
            </tbody>
    </table>
</div>