<?php
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
