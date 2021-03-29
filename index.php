<?php

require_once '_connec.php';
$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array_map('trim', $_POST);

    $errors = [];

    if (empty($data['firstname'])) {
        $errors[] = 'Your firstname is mandatory';
    }

    $firstnameLength = 45;
    if (strlen($data['firstname']) > $firstnameLength) {
        $errors[] = 'Your firstname must be maximum ' . $firstnameLength . ' characters long';
    }

    if (empty($data['lastname'])) {
        $errors[] = 'Your lastname is mandatory';
    }
    
    $lastnameLength = 45;
    if (strlen($data['lastname']) > $lastnameLength) {
        $errors[] = 'Your lastname must be maximum ' . $lastnameLength . ' characters long';
    }

    if (empty($errors)) {
        
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->execute();

        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Friends</h1>
    <ul>
        <?php foreach ($friends as $friend) : ?>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname']; ?></li>
        <?php endforeach; ?>
    </ul>

    <form action="" method="POST">
        <div>
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname">
        </div>
        <div>
            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname">
        </div>
        <div class="button">
            <button type="submit">Send</button>
        </div>
    </form>
    <?php if (!empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>