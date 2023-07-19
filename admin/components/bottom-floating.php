<!-- bottom floating components -->
<div class="d-flex align-items-center gap-2" style=" position: fixed; bottom: 20px; right: 20px;">
    <div class="counter bg-secondary rounded p-2 text-light">เลือก <h id="countDisplay">0</h> รายการ จากทั้งหมด <?php echo $number_of_results ?> รายการ</div>
    <button type="submit" id="bringToTopBtn" class="btn btn-primary" name="">
        <i class="bi bi-arrow-up-circle-fill"></i>
    </button>
</div>

<!-- checkbox and counter script -->
<script>
    // Get reference to the header checkbox
    var headerCheckbox = document.getElementById('headerCheckbox');

    // Get reference to all body checkboxes
    var bodyCheckboxes = document.querySelectorAll('.bodyCheckbox');

    // Get reference to the count display element
    var countDisplay = document.getElementById('countDisplay');

    // Function to update the count display
    function updateCountDisplay() {
        // Get the count of selected checkboxes
        var selectedCount = document.querySelectorAll('.bodyCheckbox:checked').length;

        // Display the count
        countDisplay.textContent = selectedCount;
    }

    // Add event listener to the header checkbox
    headerCheckbox.addEventListener('change', function() {
        // Get the checked status of the header checkbox
        var isChecked = headerCheckbox.checked;

        // Set the checked status of all body checkboxes accordingly
        bodyCheckboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });

        // Update the count display
        updateCountDisplay();
    });

    // Add event listener to each body checkbox
    bodyCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Update the count display
            updateCountDisplay();
        });
    });

    // Function to retrieve the selectedStdIds from the session
    function getSelectedStdIds() {
        <?php
        $selectedStdIds = isset($_SESSION['selectedStdIds']) ? $_SESSION['selectedStdIds'] : array();
        $selectedStdIdsJson = call_user_func('json_encode', $selectedStdIds);
        echo 'return ' . $selectedStdIdsJson . ';';
        ?>
    }

    // Pre-check the checkboxes for the selectedStdIds
    function preCheckCheckboxes() {
        var selectedStdIds = getSelectedStdIds();

        selectedStdIds.forEach(function(stdId) {
            var checkbox = document.querySelector('.bodyCheckbox[value="' + stdId + '"]');
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        // Update the count display
        updateCountDisplay();
    }

    // Call the preCheckCheckboxes function on page load
    window.addEventListener('load', function() {
        preCheckCheckboxes();
    });
</script>


<!-- bring to top btn script -->
<script>
    function bringToTop() {
        // Scroll the page to the top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Hide the button when it is clicked
        bringToTopBtn.classList.add("d-none");
    }

    var bringToTopBtn = document.getElementById("bringToTopBtn");
    bringToTopBtn.addEventListener("click", bringToTop);

    // Check the scroll position on page load
    window.addEventListener("load", function() {
        // Hide the button if the scroll position is already at the top
        if (window.pageYOffset === 0) {
            bringToTopBtn.classList.add("d-none");
        }
    });

    // Check the scroll position on scroll event
    window.addEventListener("scroll", function() {
        // Show or hide the button based on the scroll position
        if (window.pageYOffset > 0) {
            bringToTopBtn.classList.remove("d-none");
            bringToTopBtn.classList.add("show");
        } else {
            bringToTopBtn.classList.remove("show");
            bringToTopBtn.classList.add("d-none");
        }
    });
</script>