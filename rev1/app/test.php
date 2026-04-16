<?php
include("db.php");

$tampil= mysqli_query($mysqli, "SELECT * FROM test ORDER BY id ASC");
$login = mysqli_query($mysqli, "SELECT * FROM user ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SG</title>
</head>
<body>
    <h1>BELAJAR DATABASE</h1>
<form action="index.php" method="post" name="submit" id="nama">
  <label for="fname">nama:</label><br>
  <input type="text" id="nama" name="nama"><br>
  <label for="lname">username:</label><br>
  <input type="text" id="username" name="username"><br><br>
  <label for="lname">password:</label><br>
  <input type="text" id="password" name="password"><br><br>
  <input type="submit" value="submit" name="submit">
</form> 
<?php

    if(isset($_POST['submit'])){
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = mysqli_query($mysqli,"INSERT INTO test (nama) VALUES ('$nama')");
        $result1 = mysqli_query($mysqli,"INSERT INTO user (username, password) VALUES ('$username', '$password')");
    }
?>
<h3>Table TestSG</h3>
<table>

    <tr>
        <th>id</th>
        <th>nama</th>
        
    </tr>
    <?php
        while($user_data = mysqli_fetch_array($tampil)){
            echo "<tr>";
            echo "<td>".$user_data['id']."</td>";
            echo "<td>".$user_data['nama']."</td>";
            echo "<td><a href='edit.php?id=$user_data[id]'>Edit</a>";
            echo "<td><a href='delete.php?id=$user_data[id]'>Delete</a>";
        }
    ?>


</table>
<h3>Table User</h3>
<table>
    <tr>
    <th>username</th>
    <th>password</th>
    </tr>  
    <?php
        while($data_user = mysqli_fetch_array($login)){
            echo "<tr>";
            echo "<td>".$data_user['username']."</td>";
            echo "<td>".$data_user['password']."</td>";
            echo "<td><a href='edit.php?id=$data_user[id]'>Edit</a>";
            echo "<td><a href='delete.php?id=$data_user[id]'>Delete</a>";
        }
    ?>

    <?php
        if(isset($_POST['delete'])){
            $nama = $_POST['nama'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $result = mysqli_query($mysqli,"INSERT INTO test (nama) VALUES ('$nama')");
            $result1 = mysqli_query($mysqli,"INSERT INTO user (username, password) VALUES ('$username', '$password')");
        }
    ?>

</table>
</body>
</html>