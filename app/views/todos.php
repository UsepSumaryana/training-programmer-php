<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>todos</title>
</head>

<body>
    <a href="/../user/signOut">
        <button>logout</button>
    </a>
    <h1>To Do List</h1>
    <a href="/../todos/create">
        <button>Create</button>
    </a>
    <table border="1" aria-describedby="">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($data as $todo) : ?>

                <tr>
                    <td><?= $todo['id'] ?></td>
                    <td><?= $todo['title'] ?></td>
                    <td><?= $todo['description'] ?></td>
                    <td><img src="/attachments/<?= $todo['attachment']?>" width="200px" alt=""></td>
                    <td><?= $todo['status'] ?></td>
                    <td><?= $todo['due_date'] ?></td>
                    <td><?= $todo['created_at'] ?></td>
                    <td>
                        <?php if ($todo['status'] != 'completed') : ?>
                            <a href="/../todos/setDone/<?= $todo['id']?>"><button>Completed</button></a>
                        <?php endif; ?>
                        <a href="/../todos/edit/<?= $todo['id']?>"><button>Update</button></a>
                        <a href="/../todos/delete/<?= $todo['id']?>"><button>Delete</button></a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
</body>

</html>