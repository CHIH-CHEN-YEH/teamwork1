<?php
require_once("../db_connect.php");
$sql = "SELECT * FROM product ORDER BY id ASC";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <title>create-video</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />


    <?php include("../css.php") ?>

    <style>
        #productImage {
            max-width: 150px;
            display: none;
        }
    </style>

</head>

<body>
    <?php include("../nav.php") ?>
    <?php include("../sidebar.php") ?>



    <div class="main-content">
        <div class="container">
            <h1 class="pt-4 ">新增影片</h1>
            <div class=" py-3">
                <a href="videos.php" title="回影片列表" class="btn btn-secondary"><i class="fa-solid fa-left-long"></i></a>
            </div>
            <form id="videoForm" action="docreate-video.php" method="post">
                <div class="mb-2">
                    <label class="form-label" for="title"><span class="text-danger">*</span>標題Title</label>
                    <input type="text" class="form-control" name="title" placeholder="影片標題">
                </div>
                <div class="mb-2">
                    <label class="form-label h2 py-2" for="search"></label>
                    <input type="text" class="form-control mb-3" id="search" placeholder="輸入商品名稱以搜尋">
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="select" >
                        <option selected>選擇相關聯商品</option>
                        <?php foreach ($rows as $product): ?>
                            <option value="<?= $product["id"] ?>" data-image="<?= $product["image"] ?>"><?= $product["name"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-2">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr class="text-center">
                                <th class="h4">目前選擇產品ID</th>
                                <th class="h4">目前選擇產品名稱</th>
                                <th class="h4">商品圖片</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                                <td id="id" class="h4">未選擇</td>
                                <td id="name" class="h4">未選擇</td>
                                <td id="img-box"><img id="productImage" src="" alt="商品預覽圖片"></td>
                            </tr>
                            <input type="hidden" name="id" value="" id="hiddenInput">
                        </tbody>
                    </table>
                </div>
                <div class="mb-2">
                    <label class="form-label" for="url"><span class="text-danger">*</span>YT網址</label>
                    <input type="text" class="form-control" name="yt_url" id="youtubeUrl" placeholder="輸入網址">
                    <button type="button" id="convertButton" class="btn btn-primary my-2" >轉換</button>
                    <p class="py-2">顯示嵌入 URL:</p>
                    <input type="text" id="embedUrl" readonly size="50">
                    <p class=" my-2">預覽:</p>
                    <iframe id="videoPreview" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="mb-2">
                    <label class="form-label" for="Video_duration">影片長度</label>
                    <input type="text" class="form-control" name="Video_duration" placeholder="0:00">
                </div>
                <input type="hidden" name="valid" value="true">
                <button type="submit" id="submitButton" class="btn btn-primary">送出</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    const search = document.querySelector("#search");
    const select = document.querySelector("#select");

    search.addEventListener("input", function() {
        const filter = search.value.toLowerCase();
        const options = select.querySelectorAll("option:not([value=''])");
        let hasVisibleOptions = false;
        let firstVisibleOption = null;

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(filter)) {
                option.style.display = "";
                if (!firstVisibleOption) {
                    firstVisibleOption = option; // 記錄第一個匹配的選項
                }
                hasVisibleOptions = true;
            } else {
                option.style.display = "none";
            }
        });

        // 如果找到匹配的選項，將第一個匹配的選項設為選中狀態
        if (firstVisibleOption) {
            select.value = firstVisibleOption.value;
            select.scrollTop = firstVisibleOption.offsetTop;
        } else {
            select.selectedIndex = -1; // 清除選擇
        }

        // 如果沒有匹配的選項，顯示提示
        if (!hasVisibleOptions) {
            if (!document.querySelector("#no-match-option")) {
                const noMatchOption = document.createElement("option");
                noMatchOption.id = "no-match-option";
                noMatchOption.textContent = "無相關商品";
                noMatchOption.disabled = true;
                noMatchOption.selected = true;
                select.appendChild(noMatchOption);
            }
        } else {
            const noMatchOption = document.querySelector("#no-match-option");
            if (noMatchOption) {
                noMatchOption.remove();
            }
        }
    });
    document.getElementById("convertButton").addEventListener("click", function() {
            const youtubeUrl = document.getElementById("youtubeUrl").value;
            let videoId = youtubeUrl.split("v=")[1];
            const ampersandPosition = videoId ? videoId.indexOf("&") : -1;

            if (ampersandPosition !== -1) {
                videoId = videoId.substring(0, ampersandPosition);
            }

            if (videoId) {
                const embedUrl = "https://www.youtube.com/embed/" + videoId;
                document.getElementById("embedUrl").value = embedUrl;
                document.getElementById("videoPreview").src = embedUrl;
            } else {
                alert("Invalid YouTube URL. Please try again.");
            }
        });

        document.getElementById("videoForm").addEventListener("submit", function(event) {
            const embedUrl = document.getElementById("embedUrl").value;
            if (!embedUrl) {
                event.preventDefault();
                alert("Please convert the YouTube URL to an embed URL before submitting the form.");
            }
        });

    select.addEventListener("change", function() {
        const selectedOption = select.options[select.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.textContent;
        const imageSrc = selectedOption.getAttribute("data-image");

        document.querySelector("#id").textContent = productId ? productId : "未選擇";
        document.querySelector("#name").textContent = productName ? productName : "未選擇";
        document.querySelector("#hiddenInput").value = productId;

        if (imageSrc) {
            document.querySelector("#productImage").src = "../product_img/" + encodeURIComponent(imageSrc);
            document.querySelector("#productImage").style.display = "block";
        } else {
            document.querySelector("#productImage").style.display = "none";
        }
    });
});
    </script>
</body>

</html>