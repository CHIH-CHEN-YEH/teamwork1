<?php
require_once("../db_connect.php");
$sql = "SELECT * FROM product ORDER BY id ASC";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);




?>
<!doctype html>
<html lang="en">

<head>
    <title>新增租借商品</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />


    <?php include("../css.php"); ?>
</head>

<body>
    <?php include("../nav.php") ?>
    <?php include("../sidebar.php") ?>

    <div class="main-content">
        <div class="container py-4">
            <h1>新增租借商品</h1>
            <div class="py-2">
                <a class="btn btn-primary" href="products.php" title="回產品列表"><i class="fa-solid fa-left-long"></i></a>
            </div>
            <form action="doCreateProduct.php" method="post">
                <div class="mb-2">
                    <label class="form-label" for="name">租借產品</label>
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="select">
                        <option selected>選擇要出租的商品</option>
                        <?php foreach ($rows as $product): ?>
                            <option value="<?= $product["name"] ?>" project_id="<?=$product["id"]?>"> <?= $product["name"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (isset( $product["name"])) : ?>
                <div class="mb-2">
                    <h5>目前選擇產品ID</h5>
                    <p id="product-id"> </p>
                    <h5>目前選擇產品名稱</h5>
                    <p id="product-name"> </p>
                <?php endif; ?>
                    
                </div>
                <div class="mb-2">
                    <label class="form-label" for="price">產品租借價格</label>
                    <input type="text" class="form-control" name="price">
                </div>

                <div class="mb-2">
                    <label class="form-label" for="deposit">產品租借押金</label>
                    <input type="text" class="form-control" name="deposit">
                </div>

                <div class="mb-2">
                    <label class="form-label" for="amount">新增商品數量</label>
                    <input type="text" class="form-control" name="amount">
                </div>

                <button class="btn btn-primary" type="submit">送出</button>
            </form>
        </div>
    </div>
    <script>
        var myselect = document.getElementById("select");
        var index = myselect.selectedIndex;
        var value = myselect.options[index].value;
        var project_id = myselect.options[index].getAttribute('project_id');
        var url = myselect.options[index].getAttribute('url');
    </script>
</body>

</html>