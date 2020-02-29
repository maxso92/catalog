<?php
include_once 'config/db.php';
include_once 'app/db.php';
include_once 'functions/functions.php';

include_once 'web/header.php';

?>

    <h4>Управление категориями </h4>

<a href="index.php">Вернуться назад</a> <br>

<?php

// Форма активна или нет
$form = $_GET['form'];

// Добавление и изменение категории
if($_GET['get']=='add_category'){
    $name = $_POST['name'];

    if($_GET['edit']=='1'){
        $id_category = $_GET['id'];

        if($name) {
            $sql = "UPDATE categories SET name='$name' WHERE id='$id_category'";

            if (mysqli_query($link, $sql)) {
                echo "<div class=\"alert alert-primary\" role=\"alert\">Наименование измененено!</div>";
            } else {
                echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
            }
        }

    }else{
        if($name) {
            $sql = "INSERT INTO categories (name) VALUES ('$name')";

            if (mysqli_query($link, $sql)) {
                echo "<div class=\"alert alert-primary\" role=\"alert\">Новая категория успешно добавлена!</div>";
            } else {
                echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
            }
        }

    }


}
// Удаление категории
if($_GET['get']=='delete') {
    $id_category = $_GET['id'];

    if($id_category) {
        $sql = "DELETE FROM categories WHERE id='$id_category'";

        if (mysqli_query($link, $sql)) {
            echo "<div class=\"alert alert-primary\" role=\"alert\">Категория удалена!</div>";
        } else {
            echo "Ошибка: " . $sql . "<br>" . mysqli_error($link);
        }
    }
}


// + Форма изменения наименования
if($_GET['get']=='edit'){
    $id_category = $_GET['id'];
    $edit = '&edit=1&id='.$id_category;
    $query = "SELECT * FROM `categories` WHERE id=$id_category ";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $name =  $row["name"];


}

?>

<?php if($form =='1') { ?>

<form method="post" action="Category.php?get=add_category<?=$edit?>">
    <div class="form-label-group">
        <input type="text" name="name" class="form-control" placeholder="Наименование" value="<?=$name?>" required="" autofocus="">
    </div>
    <br>
    <button class="btn btn-lg btn-primary " type="submit">Сохранить</button>
</form>
<?php }?>
<?php
include_once 'web/footer.php';
?>