<?php

// Connection
include './config/dataBase.php';

$sql = "SELECT * FROM todo_list";
$result = mysqli_query($conn, $sql);

// fetch result rows as an arr
$todos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free result from memory
mysqli_free_result($result);

$title = 'Todo List';

$name = '';
// Add
if (isset($_POST['name'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sql = "INSERT INTO todo_list (todo_name) VALUES ('$name')";

    if (empty($name)) {
        echo 'Please enter something todo';

    } else {
        if (mysqli_query($conn, $sql)) {
            echo 'Record added';
            header('Location: index.php');

        } else {
            // err
            echo 'query error:' . mysqli_error($conn);
        };
    }
}

// Delete
if (isset($_POST['delete'])) {

    $id = mysqli_real_escape_string($conn, $_POST['id_to_del']);;
    $sql = "DELETE FROM todo_list WHERE todo_id = $id";

    if (mysqli_query($conn, $sql)) {
        echo 'Record deleted';
        header('Location: index.php');

    } else {
        // err
        echo 'query error:' . mysqli_error($conn);
    };
}

// Edit
if (isset($_POST['edit'])) {

    $edit = mysqli_real_escape_string($conn, $_POST['name_to_edit']);

    if (empty($edit)) {
        echo 'Please enter a the todo you want to edit';

    } else {

        $id = $_POST['id_to_edit'];
        $sql = "UPDATE todo_list SET todo_name = '$edit' WHERE todo_id = $id";

        if (mysqli_query($conn, $sql)) {    
            echo 'Record edited';
            header('Location: index.php');

        } else {
            echo 'query error:' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Php todo</title>
</head>

<body>
    <center>
        <h1>
            <?php echo $title; ?>
        </h1>

        <!-- ADD form -->
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <h2>What do you want to do ?</h2>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <button type="submit" name="submit">Add</button>
        </form>

        <!-- The Todos array -->
        <?php foreach ($todos as $todo) { ?>
            <h2>
                <?php echo htmlspecialchars($todo['todo_name']); ?>
                <?php echo htmlspecialchars($todo['todo_id']); ?>

                <!-- Delete -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="id_to_del" value="<?php echo $todo['todo_id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>

                <!-- Edit -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="text" name="name_to_edit" value="<?php echo $todo['todo_name']; ?>">
                    <input type="hidden" name="id_to_edit" value="<?php echo $todo['todo_id']; ?>">
                    <button type="submit" name="edit">Edit</button>
                </form>
            </h2>
        <?php } ?>

    </center>
</body>

</html>