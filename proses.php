<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "bukutamu_db");

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    if (empty($nama) || empty($email) || empty($pesan)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header("Location: index.php");
        exit();
    }

    if (strlen($nama) > 50 || strlen($email) > 50) {
        $_SESSION['error'] = "Panjang nama atau email melebihi batas!";
        header("Location: index.php");
        exit();
    }

    // Siapkan dan eksekusi query insert
    $stmt = $conn->prepare("INSERT INTO `pesan`(`nama`, `email`, `pesan`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $pesan);

    try {
        $stmt->execute();
        $_SESSION['success'] = "Pesan berhasil dikirim!";
    } catch (mysqli_sql_exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    }


    // Tutup koneksi
    $stmt->close();
    $conn->close();

    // Redirect ke halaman utama
    header("Location: index.php");
    exit();
} else {
    echo "Metode request tidak valid.";
}