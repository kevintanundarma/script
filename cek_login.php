<?php 
// mengaktifkan session pada php
session_start();
 
// menghubungkan php dengan koneksi database
include 'config.php';
 
// menangkap data yang dikirim dari form login
$username = $_POST['username'];
$password = $_POST['password'];
 
 
// menyeleksi data user dengan username dan password yang sesuai
$sql = mysqli_query($conn,"SELECT * FROM tb_admin_user where username='$username' and password='$password'");
// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($sql);
 
// cek apakah username dan password di temukan pada database
if($cek > 0){
 
	$data = mysqli_fetch_assoc($sql);
 
	// cek jika user login sebagai admin
	if($data['status']=="admin"){
 
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['status'] = "admin";
		// alihkan ke halaman dashboard admin
		header("location:berhasil_login.php");
 
	// cek jika user login sebagai user
	}else if($data['status']=="user"){
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['status'] = "user";
		// alihkan ke halaman dashboard user
		header("location:berhasil_login_user.php");
 
	}else{
 
		// alihkan ke halaman login kembali
		header("location:index.php");
    }
	}else {
        header("location:index.php");
    }	 

 
?>