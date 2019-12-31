<?php
require_once "includes/dbConexao.php";

$sql = "SELECT";
$sql .= "  id";
$sql .= " ,cat_nome";
$sql .= "  FROM categorias";
$sql .= " ORDER BY id";

$rs_cursor3 = mysqli_query($conexao, $sql);

require "status_acesso_db.php";

$rs_cursor3_total_registros = mysqli_num_rows($rs_cursor3);

for ($i = 0; $i < $rs_cursor3_total_registros; $i++) {
    extract(mysqli_fetch_array($rs_cursor3), EXTR_PREFIX_ALL, "rs_cursor3");
    require "status_acesso_db.php";
    ?>
    <a href="categorias.php?id-categoria=<?php print $rs_cursor3_id; ?>&cat-nome=<?php print $rs_cursor3_cat_nome; ?>&ordenar=1" class="link-menu-sup"><?php print $rs_cursor3_cat_nome; ?></a>&nbsp;|&nbsp;
    <?php
}
mysqli_free_result($rs_cursor3);
?>
<a href="index.php" class="link-menu-sup">Home</a>