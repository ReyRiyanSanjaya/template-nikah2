<?php 
include 'koneksi.php';


$author = $_POST['author'];
$comment = $_POST['comment'];
$date = $_POST['date'];
 
$query = mysqli_query($conn,"INSERT INTO `komen` (`author`, `comment`, `tanggal`) VALUES ('$author', '$comment', '$date');");

// echo $id;
// echo $author;
// echo $comment;
// echo $date;

// header("location:index.php?pesan=input");
?>