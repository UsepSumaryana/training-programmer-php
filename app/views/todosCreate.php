<?php
    if (empty($id)) {
        $action = "/../todos/create";
    } else {
        $action = "/../todos/edit/$id";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos</title>
</head>

<body>
    <?php if (empty($id)) : ?>
        <h2>Add New Todos</h2>
    <?php else : ?>
        <h2>Edit Todos</h2>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post"  action="<?= $action ?>" enctype="multipart/form-data">

        <div class="form-group">
            <label>Title</label>
            <input required type="text" name="title" id="title" value="<?= $title ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="description"><?= $description ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Due date</label>
            <input type="date" name="dueDate" id="dueDate" value="<?= $dueDate ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Attachment</label>
            <input type="file" name="attachment" id="attachment">
        </div>
        
        <button type="submit" id="submit" name="submit">Submit</button>

    </form>
</body>

</html>
