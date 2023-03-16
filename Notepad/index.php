<?php
$error = '';
if (isset($_POST['note']) && isset($_POST['filename'])) {
    $note = $_POST['note'];
    $filename = $_POST['filename'];
    if (!empty($filename)) {
        if (!isset($_POST['original_filename']) || $_POST['original_filename'] !== $filename . '.txt') {
            if (file_exists($filename . '.txt')) {
                $error = 'Ya existe una nota con ese nombre. Por favor elige otro nombre.';
            } else {
                if (isset($_POST['original_filename'])) {
                    rename($_POST['original_filename'], $filename . '.txt');
                }
                file_put_contents($filename . '.txt', $note);
            }
        } else {
            file_put_contents($filename . '.txt', $note);
        }
    } else {
        $error = 'Debes especificar un nombre para la nota.';
    }
}

if (isset($_GET['delete'])) {
    if (file_exists($_GET['delete'])) {
        unlink($_GET['delete']);
    }
}

$notes = glob('*.txt');

$selected_note = '';
$selected_filename = '';
if (isset($_GET['edit'])) {
    if (file_exists($_GET['edit'])) {
        $selected_note = file_get_contents($_GET['edit']);
        $selected_filename = basename($_GET['edit'], '.txt');
    }
}
?>

<?php if (!empty($error)): ?>
<p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="original_filename" value="<?php echo htmlspecialchars($selected_filename); ?>.txt">
    <label for="filename">Nombre del archivo:</label>
    <input type="text" id="filename" name="filename" value="<?php echo htmlspecialchars($selected_filename); ?>"><br><br>
    <label for="note">Nota:</label><br>
    <textarea id="note" name="note"><?php echo htmlspecialchars($selected_note); ?></textarea><br><br>
    <input type="submit" value="Guardar">
</form>

<h2>Notas guardadas</h2>
<ul>
<?php foreach ($notes as $note): ?>
    <li>
        <a href="<?php echo htmlspecialchars($note); ?>"><?php echo htmlspecialchars(basename($note, '.txt')); ?></a> |
        <a href="?edit=<?php echo urlencode($note); ?>">Editar</a> |
        <a href="?delete=<?php echo urlencode($note); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta nota?');">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul>