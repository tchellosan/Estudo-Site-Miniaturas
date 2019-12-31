<?php require_once "includes/dbConexao.php"; ?>
<form id="formSubcateg" name="formSubcateg" method="GET" action="subcategoria.php">
    <div class="logo">
        <a href="index.php">
            <img src="img/logo_fs.gif" alt="Miniaturas">
        </a>
    </div>
    <div class="menu-compras">
        <a href="pedidos.php" class="link-cadastro">Meus Pedidos</a>
        <a href="login.php?meu_cadastro" class="link-cadastro">Meu Cadastro</a>
        <a href="cesta.php" class="link-carrinho">Meu Carrinho
            <span class="c_verde">(
                <?php
                if ((isset($_SESSION['num_ped']) and isset($_SESSION['total_itens']))
                        and ( $_SESSION['num_ped'] <> "" and $_SESSION['total_itens'] > 0)) {
                    if ($_SESSION['total_itens'] == 1) {
                        print $_SESSION['total_itens'] . " produto";
                    } else {
                        print $_SESSION['total_itens'] . " produtos";
                    }
                } else {
                    print "vazio";
                }
                ?>
                )</span>
        </a>
    </div>
    <div class="pesq-subcat">
        <img src="img/marcador_lupa.gif" alt="Pesquisar">
        <select name="subcateg" id="subcateg">
            <option value="">-- Selecione</option>
            <?php
            $sql = " SELECT  subcateg, COUNT(subcateg) AS total_subcat ";
            $sql .= "  FROM miniaturas ";
            $sql .= " GROUP BY subcateg ";
            $sql .= " ORDER BY subcateg ";

            $rs_cursor2 = mysqli_query($conexao, $sql);
            require "status_acesso_db.php";

            $rs_cursor2_total_registros = mysqli_num_rows($rs_cursor2);

            for ($i = 0; $i < $rs_cursor2_total_registros; $i++) {
                extract(mysqli_fetch_array($rs_cursor2), EXTR_PREFIX_ALL, "rs_cursor2");
                require "status_acesso_db.php";
                ?>
                <option value="<?php print $rs_cursor2_subcateg; ?>"><?php print $rs_cursor2_subcateg . " (" . $rs_cursor2_total_subcat . ")"; ?></option>
                <?php
            }
            mysqli_free_result($rs_cursor2);
            ?>
        </select>
    </div>
    <div class="btn-pesquisa">
        <button type="submit"><img src="img/btn_ok.gif" alt="Pesquisar"></button>
    </div>
</form>
