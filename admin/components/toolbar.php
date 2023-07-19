<div class="row my-2 d-flex">
    <div class="col-sm">
        <div class="d-flex align-items-center gap-3">
            <label for="itemsPerPage">แสดง</label>
            <select class="form-select" id="itemsPerPage" name="itemsPerPage" onchange="changeItemsPerPage()">
                <option value="10" <?php if ($results_per_page == 10) echo 'selected'; ?>>10</option>
                <option value="25" <?php if ($results_per_page == 25) echo 'selected'; ?>>25</option>
                <option value="50" <?php if ($results_per_page == 50) echo 'selected'; ?>>50</option>
                <option value="100" <?php if ($results_per_page == 100) echo 'selected'; ?>>100</option>
            </select>
            <label for="">รายการ</label>
        </div>
    </div>
    <div class="col-sm">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="addRecord">
            เพิ่มรายการ
        </button>
        <a class="btn btn-primary" href="../export.php">ส่งออกไฟล์ .csv</a>
    </div>
    <div class="col-sm">
        <div class="search">
            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="get" class="d-flex gap-2">
                <input type="text" name="keyword" id="" class="form-control" placeholder="ค้นหา...">
                <button type="submit" class="btn btn-primary" id="searchButton">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function changeItemsPerPage() {
        var itemsPerPage = document.getElementById("itemsPerPage").value;
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);
        url.searchParams.set("results_per_page", itemsPerPage);
        window.location.href = url.toString();
    }
</script>