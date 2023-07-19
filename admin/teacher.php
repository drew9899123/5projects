<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- change this title -->
    <title>บุคลากร</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">

        <?php
        // define how many results you want per page
        $results_per_page = isset($_GET['results_per_page']) ? $_GET['results_per_page'] : 10;

        // connect to the database
        // include '../connect.php';

        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page - 1) * $results_per_page;

        // retrieve selected results from the database and display them on the page
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'teacher_id'; //change this in different pages 
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        //check if has searching...
        if (isset($_GET['keyword'])) {
            $_SESSION['keyword'] = $_GET['keyword'];
            $keyword = $_SESSION['keyword'];
            //change these sql commands in different page
            $count_sql = "  SELECT COUNT(*) AS total FROM 
                                (SELECT teacher_id, CONCAT_WS(' ', name, surname) AS teacher_name
                                FROM teacher
                                WHERE teacher_id LIKE '%$keyword%' OR CONCAT(name, ' ', surname) LIKE '%$keyword%'
                                ) AS subquery;
                            ";
            $search_sql = " SELECT teacher_id, CONCAT_WS(' ', name, surname) AS teacher_name
                            FROM teacher
                            WHERE teacher_id LIKE '%$keyword%' OR CONCAT(name, ' ', surname) LIKE '%$keyword%'
                            ORDER BY 
                                $sortColumn $sortOrder
                            LIMIT 
                                $this_page_first_result, $results_per_page
                            ";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        } else {
            //change these sql commands in different page
            $count_sql = "SELECT COUNT(*) AS total FROM teacher";
            $search_sql = " SELECT teacher_id, CONCAT_WS(' ', name, surname) AS teacher_name
                            FROM teacher
                            ORDER BY $sortColumn $sortOrder
                            LIMIT $this_page_first_result, $results_per_page";

            $count_result = mysqli_query($con, $count_sql);
            $count_row = mysqli_fetch_assoc($count_result);
            $number_of_results = $count_row['total'];

            $result = mysqli_query($con, $search_sql);
        }

        // determine number of total pages available
        $number_of_pages = ceil($number_of_results / $results_per_page);
        ?>

        <!-- change this header-->
        <div class="header">
            <h1>ข้อมูลบุคลากร</h1>

            <?php
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            ?>
                <div class="alert alert-success p-2" role="alert">
                    <?php
                    echo '<i class="bi bi-check-circle-fill"></i> ' . $_GET['keyword'] . ' (' . $number_of_results . '  รายการ)';
                    ?>
                </div>
            <?php
            }
            ?>
        </div>

        <?php
        //items per page & search bar
        include 'components/toolbar.php';

        //change these lines in different page
        echo '<table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" class="form-check-input" id="headerCheckbox"></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=teacher_id&order=' . ($sortColumn == 'teacher_id' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">ลำดับบุคลากร</a></th>
                        <th scope="col"><a href="?page=' . $page . '&sort=teacher_name&order=' . ($sortColumn == 'teacher_name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '&keyword=' . $keyword . '">ชื่อ-สกุล</a></th>
                        <th scope="col"><a href="#">จัดการ</a></th>
                        <!-- Add more column headers with their respective sort links -->
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_array($result)) {
            //change these line in different page
            echo '<tr>
                    <td><input type="checkbox" class="bodyCheckbox form-check-input"></td>
                    <td>' . $row['teacher_id'] . '</td>
                    <td>' . $row['teacher_name'] . '</td>
                    <td class="d-flex justify-content-center">
                        <a href="edit_teacher.php?teacher_id=' . $row['teacher_id'] . '" type="submit" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                    </td>
                    <!-- Add more table cells for additional columns -->
                </tr>';
        }

        echo '</tbody>
            </table>';

        // display the links to the pages
        $range = 5; // Number of page links to display
        $start = max(1, $page - $range);
        $end = min($number_of_pages, $page + $range);

        echo '<nav aria-label="Pagination">
                <ul class="pagination justify-content-center">';

        if ($page > 1) {
            echo '<li class="page-item">
                    <a class="page-link" href="?page=' . ($page - 1) . '&sort=' . $sortColumn . '&order=' . $sortOrder . '&keyword=' . generateKeywordQueryParam($keyword) .  '">Previous</a>
                </li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">' . $i . '</a>
                    </li>';
            } else {
                echo '<li class="page-item">
                        <a class="page-link" href="?page=' . $i . '&sort=' . $sortColumn . '&order=' . $sortOrder . '&keyword=' . generateKeywordQueryParam($keyword) . '">' . $i . '</a>
                    </li>';
            }
        }

        if ($page < $number_of_pages) {
            echo '<li class="page-item">
                    <a class="page-link" href="?page=' . ($page + 1) . '&sort=' . $sortColumn . '&order=' . $sortOrder . '&keyword=' . generateKeywordQueryParam($keyword) .  '">Next</a>
                </li>';
        }

        echo '</ul>
            </nav>';

        // display the current page and total pages information
        echo '<p class="text-center">หน้า ' . $page . ' จาก ' . $number_of_pages . '</p>';

        function generateKeywordQueryParam($keyword)
        {
            if (!empty($keyword)) {
                return '&keyword=' . urlencode($keyword);
            }
            return '';
        }
        include 'components/bottom-floating.php';

        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>

</html>