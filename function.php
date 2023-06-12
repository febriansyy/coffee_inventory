<?php 
    session_start();
    
    // Membuat koneksi ke database
    $conn = mysqli_connect("localhost", "root", "",  "stockbarang");

    // Menambah barang baru
    if(isset($_POST['addnewbarang'])){
        $namabarang = $_POST['namabarang'];
        $deskripsi = $_POST['deskripsi'];
        $stock = $_POST['stock'];

        $add_to_table = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock) values('$namabarang', '$deskripsi', '$stock')");

        if($add_to_table){
            header('location: index.php');
        } else {
            echo 'gagal';
            header('location: index.php');
        }
    };

    // Menambah barang masuk
    if(isset($_POST['barangmasuk'])){
        $barangnya = $_POST['barangnya'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);

        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstocksekarangdenganqty = $stocksekarang+$qty;

        $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, keterangan, qty) values('$barangnya', '$penerima', '$qty')");
        $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganqty' where idbarang='$barangnya'");
        if($addtomasuk&&$updatestockmasuk){
            header('location: masuk.php');
        } else {
            echo 'gagal';
            header('location: masuk.php');
        }
    };

    // Menambah barang keluar
    if(isset($_POST['addbarangkeluar'])){
        $barangnya = $_POST['barangnya'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);

        $stocksekarang = $ambildatanya['stock'];

        if($stocksekarang >= $qty){
            $tambahkanstocksekarangdenganqty = $stocksekarang-$qty;
            $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values('$barangnya', '$penerima', '$qty')");
            $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganqty' where idbarang='$barangnya'");
            if($addtokeluar&&$updatestockmasuk){
                header('location: keluar.php');
            } else {
                echo 'gagal';
                header('location: keluar.php');
            }
        } else {
            echo '
            <script>
                alert("Stock Saat Ini Tidak Mencukupi");
                window.location.href="keluar.php";
            </script>
            ';
        }
    };

    // Update Info Barang
    if(isset($_POST['updatebarang'])){
        $idb = $_POST['idb'];
        $idm = $_POST['idmasuk'];
        $namabarang = $_POST['namabarang'];
        $deskripsi = $_POST['deskripsi'];

        $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
        if($update){
            header('location:index.php');
        }else {
            header('location:index.php');
        }
    };

    // Hapus Barang dari Stock
    if(isset($_POST['hapusbarang'])){
        $idb = $_POST['idb'];

        $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
        if($hapus){
            header('location:index.php');
        }else {
            header('location:index.php');
        }
    };

    // Mengubah data barang masuk
    if(isset($_POST['updatebarangmasuk'])){
        $idb = $_POST['idb'];
        $idm = $_POST['idm'];
        $deskripsi = $_POST['keterangan'];
        $qty = $_POST['qty'];

        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stocksekarang = $stocknya['stock'];

        $qtysekarang = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
        $qtynya = mysqli_fetch_array($qtysekarang);
        $qtysekarang = $qtynya['qty'];

        if($qty>$qtysekarang){
            $selisih = $qty - $qtysekarang;
            $kurangin = $stocksekarang + $selisih;
            $kuranginstock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
            
            if($kuranginstock&&$updatenya){
                header('location:masuk.php');
            } else {
                header('location:mausk.php');
            }
        } else {
            $selisih =$qtysekarang - $qty;
            $kurangin = $stocksekarang - $selisih;
            $kuranginstock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
            
            if($kuranginstock&&$updatenya){
                header('location:masuk.php');
            } else {
                header('location:mausk.php');
            }
        }
    };

    // Menghapus barang masuk
    if(isset($_POST['hapusbarangmasuk'])){
        $idb = $_POST['idb'];
        $qty = $_POST['kty'];
        $idm = $_POST['idm'];

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stok = $data['stock'];
        $selisih = $stok - $qty;
        $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idmasuk='$idm'");

        if($update&&$hapusdata){
            header('location:masuk.php');
        } else {
            header('location:masuk.php');
        }
    }


    // Mengubah data barang keluar
    if(isset($_POST['updatebarangkeluar'])){
        $idb = $_POST['idb'];
        $idk = $_POST['idk'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stocksekarang = $stocknya['stock'];

        $qtysekarang = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
        $qtynya = mysqli_fetch_array($qtysekarang);
        $qtysekarang = $qtynya['qty'];

        if($qty>$qtysekarang){
            $selisih = $qty - $qtysekarang;
            $kurangin = $stocksekarang - $selisih;
            $kuranginstock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'"); 
            if($kuranginstock&&$updatenya){
                header('location:keluar.php');
            } else {
                header('location:keluar.php');
            }
        } else {
            $selisih =$qtysekarang + $qty;
            $kurangin = $stocksekarang - $selisih;
            $kuranginstock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
            
            if($kuranginstock&&$updatenya){
                header('location:keluar.php');
            } else {
                header('location:keluar.php');
            }
        }
    };

    // Menghapus barang keluar
    if(isset($_POST['hapusbarangkeluar'])){
        $idb = $_POST['idb'];
        $qty = $_POST['kty'];
        $idk = $_POST['idk'];

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stok = $data['stock'];
        $selisih = $stok - $qty;
        $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

        if($update&&$hapusdata){
            header('location:keluar.php');
        } else {
            header('location:keluar.php');
        }
    }
?>