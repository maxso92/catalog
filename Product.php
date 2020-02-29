<?php
include_once 'config/db.php';
include_once 'app/db.php';
include_once 'functions/functions.php';

include_once 'web/header.php';

?>
    <h4>Управление продуктами </h4>

<a href="index.php">Вернуться назад</a> <br>

<?php

// Форма активна или нет
$form = settype($_GET['form'], 'integer' );

// Добавление и изменение категории
if($_GET['get']=='add_product'){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $description_full = $_POST['description_full'];
    $id_category = $_POST['id_category'];

    if($_GET['edit']=='1'){
        $id_product = $_GET['id'];

        if($name) {
            $sql = "UPDATE products SET name='$name',description='$description',description_full='$description_full',id_category='$id_category'  WHERE id='$id_product'";

            if (mysqli_query($link, $sql)) {
                echo "<div class=\"alert alert-primary\" role=\"alert\">Товар успешно изменен!</div>";
            } else {
                echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
            }
        }

    }else{
        if($_FILES) {

            $uploadfile = "web/images/" . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
        }else{
            $uploadfile = '';
        }


        if(isset($name, $description, $id_category)) {
            $sql = "INSERT INTO products (name, description, description_full, id_category, image, status) VALUES ('$name','$description','$description_full','$id_category','$uploadfile', 1 )";

            if (mysqli_query($link, $sql)) {
                echo "<div class=\"alert alert-primary\" role=\"alert\">Новый товар добавлен!</div>";
            } else {
                echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
            }
        }

    }


}
// Удаление продукта
if($_GET['get']=='delete') {
    $id_product = $_GET['id'];

    if($id_product) {
        $query = "SELECT * FROM `products` WHERE id=$id_product ";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $image =  $row["image"];
        // удаляем изображение если оно есть
        if(isset($image)) {
            unlink($image);
        }

        $sql = "DELETE FROM products WHERE id='$id_product'";

        if (mysqli_query($link, $sql)) {
            echo "<div class=\"alert alert-primary\" role=\"alert\">Продукт удален!</div>";
        } else {
            echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
        }
    }
}

// Вывод информации о продукте
if($_GET['get']=='view'){
    $id_product = $_GET['id'];
    $edit = '&edit=1&id='.$id_category;
    $query = "SELECT * FROM `products` WHERE id=$id_product ";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $name =  $row["name"];
    $description =  $row["description"];
    $description_full =  $row["description_full"];
    $id_category =  $row["id_category"];
    $image =  $row["image"];

    if(isset($name)) {
        echo 'Наименование: <b>' . $name . '</b><br> ';
    }
    if(isset($description)) {

        echo 'Описание: <b>' . $description . '</b><br> ';
    }
    if(isset($description_full)) {

        echo 'Полное описание: <b>' . $description_full . '</b><br> ';
    }
    if(isset($image)) {

        echo 'Изображение: <br> <img src="'.$row['image'].'"> <br> ';
    }



}




// + Форма изменения наименования
if($_GET['get']=='edit'){
    $id_product = $_GET['id'];
    $edit = '&edit=1&id='.$id_product;
    $query = "SELECT * FROM `products` WHERE id=$id_product ";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $name =  $row["name"];
    $description =  $row["description"];
    $description_full =  $row["description_full"];
    $id_category =  $row["id_category"];


}

?>

<?php if($form =='1') { ?>

<form method="post" enctype="multipart/form-data" action="Product.php?get=add_product<?=$edit?>">
    <div class="form-label-group">
        <input type="text" name="name" class="form-control" placeholder="Наименование" value="<?=$name?>" required="" autofocus=""> <br>


        <textarea name="description" class="form-control" placeholder="Описание" ><?=$description?></textarea> <br>
        <textarea name="description_full" class="form-control" placeholder="Полное описание" ><?=$description_full?></textarea><br>

        <label>Категория:</label>

        <select  name="id_category" >

            <?php
            $query = "SELECT * FROM `categories` WHERE id=$id_category ";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_assoc($result);
            $name =  $row["name"];
            ?>
            <?php if($name ){ ?>
            <option value="<?=$row["id"]?>" selected><?=$row["name"]?></option>';
            <?php } ?>

           <?php
           $result = mysqli_query($link, "SELECT id,name FROM categories");
           while ($row = $result->fetch_assoc()) { ?>
            <option value="<?=$row['id']?>"><?=$row['name']?></option>
        <?php }?>

        </select>



        <br>
        <label>Изображение:</label>
        <input type="file" name="image"><br>


    </div>
    <br>
    <button class="btn btn-lg btn-primary " type="submit">Сохранить</button>
</form>
<?php }?>
<?php
include_once 'web/footer.php';
?>