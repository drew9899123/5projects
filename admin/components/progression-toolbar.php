<div class="row mb-3">
    <div class="col-sm">
        <button id="showSelectedBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectedModal">แสดงรายการที่เลือก</button>


        <div class="modal fade" id="selectedModal" tabindex="-1" aria-labelledby="selectedModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectedModalLabel"><i class="bi bi-people-fill"></i> นักศึกษาที่เลือก</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="selectedStdIds"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div>
            <form action="" method="post" class="d-flex gap-2">
                <select name="progression_id" id="" class="form-select">
                        <option value="">เลือกสถานะที่จะอัพเดต...</option>
                    <?php
                    $sql2 = "SELECT * FROM progression";
                    $result2 = $con->query($sql2);
                    while ($row2 = mysqli_fetch_array($result2)) {
                    ?>
                        <option value="<?php echo $row2['progression_id'] ?>">
                            <?php echo $row2['progression_name'] ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
                <input type="submit" class="btn btn-success w-25" id="" name="updateProgression" value="บันทึก" required>
            </form>
        </div>
    </div>
</div>