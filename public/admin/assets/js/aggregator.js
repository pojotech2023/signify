$(document).ready(function () {
    /** ======= SHADE SECTION ======= **/

    // Function to update the shade image input names dynamically
    function updateShadeIndexes() {
        $(".shade-row").each(function (index) {
            $(this).find("input[name^='shade_img']").attr("name", `shade_img[${index}][]`);
        });
    }

    // Function to update the add button visibility
    function updateShadeButtons() {
        $(".add-shade").remove(); // Remove all plus buttons

        let lastShadeRow = $(".shade-row").last();
        if (lastShadeRow.length) {
            lastShadeRow.find(".d-flex").append(`
                <button type="button" class="btn btn-success ms-2 add-shade" disabled><i class="fas fa-plus"></i></button>
            `);
        }
        checkShadeFields(); // Ensure button is enabled only when fields are filled
    }

    // Function to check shade fields and enable the add button
    function checkShadeFields() {
        let lastRow = $(".shade-row").last();
        let shadeName = lastRow.find("input[name='shade_name[]']").val().trim();
        let shadeImageInput = lastRow.find("input[name^='shade_img']"); // Select all file inputs

        let addButton = lastRow.find(".add-shade");

        let hasFile = false;
        if (shadeImageInput.length > 0) {
            for (let i = 0; i < shadeImageInput.length; i++) {
                if (shadeImageInput[i].files.length > 0) {
                    hasFile = true;
                    break;
                }
            }
        }

        if (shadeName !== "" || hasFile) {
            addButton.prop("disabled", false);
        } else {
            addButton.prop("disabled", true);
        }
    }

    // Initially update shade buttons for prepopulated rows
    updateShadeButtons();
    updateShadeIndexes();

    // Enable the "+" button in edit mode if there are existing values
    $(".shade-row").each(function () {
        let shadeName = $(this).find("input[name='shade_name[]']").val();
        let shadeImage = $(this).find("input[name^='shade_img']").val();
        let addButton = $(this).find(".add-shade");

        if (shadeName.trim() !== "" || shadeImage.trim() !== "") {
            addButton.prop("disabled", false);
        }
    });

    // Monitor changes in the last row fields (shade name and file input)
    $(document).on("input change", "input[name='shade_name[]'], input[name^='shade_img']", function () {
        checkShadeFields();
    });

    // Add new shade field when clicking the "+" button
    $(document).on("click", ".add-shade", function () {
        if ($(this).prop("disabled")) return;

        $("#shades-container").append(`
            <div class="row align-items-center shade-row mt-3">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Shade Name</label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <input type="text" name="shade_name[]" class="form-control">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Shade Images</label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex">
                        <input type="file" name="shade_img[][]" multiple accept=".jpg,.jpeg,.png" class="form-control">
                        <button type="button" class="btn btn-danger remove-shade ms-2"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        `);

        updateShadeIndexes();
        updateShadeButtons(); // Ensure only one "+" button exists
    });

    // Remove shade row and update last row's "+" button
    $(document).on("click", ".remove-shade", function () {
        $(this).closest(".shade-row").remove();
        updateShadeIndexes();
        updateShadeButtons();
    });
});
