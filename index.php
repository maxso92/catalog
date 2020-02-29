<?php
include_once 'config/db.php';
include_once 'app/db.php';
include_once 'functions/functions.php';

include_once 'web/header.php';

?>



<?php

$category = $_GET['category'];

if(isset($category)){
    $query = "WHERE id_category='$category'";
}


  $result = mysqli_query($link, "SELECT id,name FROM categories");


$result_products = mysqli_query($link, "SELECT * FROM products $query ORDER by id DESC");
//$result_products = mysqli_query($link, "SELECT * FROM products WHERE 'id_category'='12'");



// Скрываем и показываем продукты
  if($_GET['view']=='1'){
      $id = $_GET['id']; // id продукта
      $get = $_GET['get']; // запрос виден / не виден

      if($id) {
          $sql = "UPDATE products SET status='$get' WHERE id='$id'";

          if (mysqli_query($link, $sql)) {
              echo "<div class=\"alert alert-primary\" role=\"alert\">Товар успешно изменен!</div>";
          } else {
              echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
          }
      }

  }

?>




<br>

    <li><a href="index.php">Все категории</a></li>
<?php while ($row = $result->fetch_assoc()) { ?>
    <li><a href="?category=<?=$row["id"]?>"><?php echo $row["name"]; ?> </a> (<a href="Category.php?get=edit&id=<?=$row["id"]?>&form=1">редактировать</a> / <a href="Category.php?get=delete&id=<?=$row["id"]?>">удалить</a>)</li>
<?php } ?>

<hr>
<a href="Category.php?get=add&form=1" class="btn btn-success">Создать категорию</a>
    <a href="Product.php?get=add&form=1" class="btn btn-light">Добавить продукт</a>



<table class="table table-responsive-lg table-striped table-hover">
    <thead class="">
    <tr>
        <td>ID</td>
        <td>Изображение</td>
        <td>Категория</td>
        <td>Наименование</td>
        <td>Описание</td>
        <td>Действие</td>


    </tr>
    </thead>

    <tbody>

    <?php while ($row = $result_products->fetch_assoc()) { ?>


        <?php


        $id =$row["id_category"];

            // Получение ктегории
        $query_category = "SELECT * FROM `categories` WHERE id='$id'";
        $result_category = mysqli_query($link, $query_category);
        $row_category = mysqli_fetch_assoc($result_category);
        $category_name =  $row_category["name"];
        $category_id =  $row_category["id"];

        if(!isset($row_category["name"])){
            $category_name = '(Не указано)';
        }
        if(!isset($row["image"])){
            $image = '(Нет изображения)';
        }else{
            $image = '<img src="'.$row["image"].'" style="width: 25%;" class="img-thumbnail">';

        }
        ?>

        <tr>
            <td>
                <?=$row["id"]?>
            </td>
            <td>
                <?=$image?>
            </td>
            <td>
               <a href="?category=<?=$category_id?>"><?=$category_name?></a>
            </td>
            <td>
                <?=$row["name"]?>
            </td>
            <td>
                <?=$row["description"]?>
            </td>
            <td>
                <?php
                if($row["status"]==1){
                    $view_name = 'Скрыть';
                    $view_get = '0';
                }else{
                    $view_name = 'Показать';
                    $view_get = '1';
                }
                ?>
                <a href="?view=1&id=<?=$row["id"]?>&get=<?=$view_get?>"><?=$view_name?></a><br>

                <a href="Product.php?get=edit&id=<?=$row["id"]?>">Редактировать</a><br>
                <a href="?delete=1&id=<?=$row["id"]?>&get=<?=$view_get?>">Удалить</a>

            </td>
        </tr>
    <?php } ?>
    </tbody>

</table>



<?php
mysqli_free_result($result);
mysqli_free_result($result_products);


include_once 'web/footer.php';
?>