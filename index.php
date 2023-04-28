<?php

$mysqli = new mysqli('localhost', 'root', '', 'crud') or die(mysqli_error($mysqli));


$text = '';
$update = false;

if(isset($_POST['submit'])){
  $text = $_POST['text'];

  
  $mysqli->query("INSERT INTO items  (toDo) VALUES('$text')") or die($mysqli->error);
  
  $_SESSION['message'] = "Record Has Been saved!";
  $_SESSION['msg_type'] = "text-teal-500";

}

if(isset($_GET['delete'])){
  $id = $_GET['delete'];

  $mysqli->query("DELETE FROM items WHERE id=$id") or die($mysqli->error);
  
  $_SESSION['message'] = "Record Has Been Deleted!";
  $_SESSION['msg_type'] = "text-red-400";

}

if(isset($_GET['edit'])){
  $id = $_GET['edit'];
  $update = true;
  $result = $mysqli->query("SELECT * FROM items WHERE id=$id") or die($mysqli->error);
  
  $row = $result->fetch_array();
  $id = $row['id'];
  $text = $row['toDo'];
  
}

if(isset($_POST['update'])){
  $id = $_POST['id'];
  $text = $_POST['text'];

  $mysqli->query("UPDATE items SET toDo='$text' WHERE id = $id");

  $_SESSION['message'] = "Record Has Been Updated!";
  $_SESSION['msg_type'] = "text-blue";

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
    extend: {
      colors: {
        dark: "#18191a",
        primaryDark: "#3a3b3c",
      },
    
    },
  },
    }
  </script>
</head>
<body class="bg-dark">
  <nav class="bg-primaryDark w-full h-[50px] flex justify-center items-center text-white font-bold">
    <div>CRUD</div>
  </nav>

  <section class="w-full h-screen bg-dark flex text-white items-center flex-col gap-10">
    <div class="mt-[5rem]">
      <div>
      <?php 
          if(isset($_SESSION['message'])): ?>
          <div class="<?=$_SESSION['msg_type']?>">
              <?php
                  echo $_SESSION['message'];
                  unset($_SESSION['message']);
                  ?>
          </div>
          <?php endif ?>
      </div>
      <form action="./index.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?> ">
        <div class="flex items-center border-b border-teal-500 py-2">
          <input class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" value="<?php echo $text?>" name="text">
          <?php if($update == false):?>
            <button class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit" name="submit">ADD</button>
          <?php else: ?>
            <button class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit" name="update">UPDATE</button>
          <?php endif ?>
        </div>
      </form>
    </div>
    <div class="w-[30%] p-10">
    <?php 
        $result = $mysqli->query("SELECT * FROM items");
      ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="flex justify-between p-2">
          <div>
            <h1><?php echo $row['toDo']; ?></h1>
          </div>
          <div class="flex gap-4">
            <a href="./index.php?edit=<?php echo $row['id']; ?>" class="bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded">EDIT</a>
            <a href="./index.php?delete=<?php echo $row['id']; ?>"
                onclick="return confirm('Are you sure you want to delete the data')"
                class="bg-red-500 hover:bg-red-700 border-red-500 hover:border-red-700 text-sm border-4 text-white py-1 px-2 rounded">DELETE</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>
</body>
</html>