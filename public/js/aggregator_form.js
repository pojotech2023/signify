/** ======= Website Aggregator Form ======= **/

document.addEventListener("DOMContentLoaded", function () {
    /** ======= Category wise Subcategory Dropdown list SECTION ======= **/
    document.getElementById("category_id").addEventListener("change", function () {
        let categoryId = this.value;
        let subcategoryDropdown = document.getElementById("subcategory");

        // Show a loading option while fetching subcategories
        subcategoryDropdown.innerHTML = '<option value="">Loading...</option>';

        // Update the hidden field with the selected category ID
        document.getElementById("hidden_category_id").value = categoryId;

        if (categoryId) {
            fetch(`/api/get-subcategories/${categoryId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    // Reset subcategory dropdown
                    subcategoryDropdown.innerHTML = '<option value="">Select Sub Category</option>';

                    if (data.length > 0) {
                        data.forEach(subcategory => {
                            let option = document.createElement("option");
                            option.value = subcategory.id;
                            option.textContent = subcategory.sub_category;
                            subcategoryDropdown.appendChild(option);
                        });
                    } else {
                        subcategoryDropdown.innerHTML = '<option value="">No Subcategories Available</option>';
                    }
                })
                .catch(error => {
                    console.error("Error fetching subcategories:", error);
                    subcategoryDropdown.innerHTML = '<option value="">Error Loading Subcategories</option>';
                });
        }
    });

    /** ======= Subcategory wise Material list SECTION ======= **/
    document.getElementById("subcategory").addEventListener("change", function () {
        let subcategoryId = this.value;
        let materialSection = document.getElementById("material-section");
        let materialImagesContainer = document.getElementById("material-images");

        materialImagesContainer.innerHTML = '<p>Loading...</p>';
        materialSection.style.display = "none";

        document.getElementById("hidden_sub_category_id").value = subcategoryId;

        if (subcategoryId) {
            fetch(`/api/get-materials/${subcategoryId}`)
                .then(response => response.json())
                .then(data => {
                    materialImagesContainer.innerHTML = "";

                    if (data.length > 0) {
                        data.forEach(material => {
                            let imagePath = `/storage/${material.main_img}`;

                            // Collect sub-images from separate columns
                            let subImages = [];
                            if (material.sub_img1) subImages.push(`/storage/${material.sub_img1}`);
                            if (material.sub_img2) subImages.push(`/storage/${material.sub_img2}`);
                            if (material.sub_img3) subImages.push(`/storage/${material.sub_img3}`);
                            if (material.sub_img4) subImages.push(`/storage/${material.sub_img4}`);

                            let colDiv = document.createElement("div");
                            colDiv.className = "col-md-3 mb-3";
                            colDiv.innerHTML = `
                                <div class="card">
                                    <img src="${imagePath}" class="card-img-top material-card" data-material-id="${material.id}"
                                     alt="${material.material_name}" style="cursor: pointer;">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <p class="card-text">${material.material_name}</p>
                                        ${subImages.length > 0 ? `<span class="plus-icon" data-sub-imgs='${JSON.stringify(subImages)}' style="cursor: pointer;">➕</span>` : ''}
                                    </div>
                                </div>
                            `;
                            materialImagesContainer.appendChild(colDiv);
                        });

                        materialSection.style.display = "block";

                        // Attach click event to plus icons
                        document.querySelectorAll(".plus-icon").forEach(icon => {
                            icon.addEventListener("click", function () {
                                let subImages = JSON.parse(this.getAttribute("data-sub-imgs"));
                                showSubImagesPopup(subImages);
                            });
                        });
                    } else {
                        materialImagesContainer.innerHTML = '<p>No materials found.</p>';
                    }
                })
                .catch(error => {
                    console.error("Error fetching materials:", error);
                    materialImagesContainer.innerHTML = '<p>Error loading materials.</p>';
                });
        }
    });

    /** ======= Material wise Shades list SECTION ======= **/
    document.getElementById("material-images").addEventListener("click", function (event) {
        let card = event.target.closest(".material-card");
        if (card) {
            let materialId = card.getAttribute("data-material-id");
            document.getElementById("hidden_material_id").value = materialId;
            loadShades(materialId);
        }
    });

    function loadShades(materialId) {
        let shadesSection = document.getElementById("shades-section");
        let shadeImagesContainer = document.getElementById("shade-images");

        shadeImagesContainer.innerHTML = '<p>Loading...</p>';
        shadesSection.style.display = "none";

        fetch(`/api/get-shades/${materialId}`)
            .then(response => response.json())
            .then(data => {
                shadeImagesContainer.innerHTML = "";

                if (data.length > 0) {
                    data.forEach(shade => {
                        let shadeImagePath = `/storage/${shade.shade_img}`;
                        let shadeDiv = document.createElement("div");
                        shadeDiv.className = "col-md-3 mb-3 shade-card";
                        shadeDiv.dataset.shadeId = shade.id;
                        shadeDiv.innerHTML = `
                            <div class="card" style="cursor: pointer;">
                                <img src="${shadeImagePath}" class="card-img-top" alt="${shade.shade_name}">
                                <div class="card-body">
                                    <p class="card-text text-center">${shade.shade_name}</p>
                                </div>
                            </div>
                        `;
                        shadeImagesContainer.appendChild(shadeDiv);
                    });
                    shadesSection.style.display = "block";
                } else {
                    shadeImagesContainer.innerHTML = '<p>No shades found.</p>';
                }
            })
            .catch(error => {
                console.error("Error fetching shades:", error);
                shadeImagesContainer.innerHTML = '<p>Error loading shades.</p>';
            });
    }

    // Capture selected shade
    document.getElementById("shade-images").addEventListener("click", function (event) {
        let shadeCard = event.target.closest(".shade-card");
        if (shadeCard) {
            let shadeId = shadeCard.getAttribute("data-shade-id");

            // Update hidden input
            document.getElementById("hidden_shade_id").value = shadeId;

            // Remove active class from all shade cards
            document.querySelectorAll(".shade-card").forEach(card => {
                card.classList.remove("selected-shade");
            });

            // Add active class to the selected shade
            shadeCard.classList.add("selected-shade");
        }
    });

    // Add CSS to highlight selected shade
    let style = document.createElement("style");
    style.innerHTML = `
        .selected-shade {
            border: 2px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
    `;
    document.head.appendChild(style);

    /** ======= Show Popup with Sub-Images and Navigation ======= **/
    function showSubImagesPopup(subImages) {
        if (subImages.length === 0) {
            alert("No additional images available.");
            return;
        }

        let popupContainer = document.createElement("div");
        popupContainer.id = "subImagesPopup";
        popupContainer.classList.add("popup-container");

        let popupContent = document.createElement("div");
        popupContent.classList.add("popup-content");

        let closeButton = document.createElement("span");
        closeButton.innerHTML = "&times;";
        closeButton.classList.add("close-button");
        closeButton.addEventListener("click", () => document.body.removeChild(popupContainer));

        let leftArrow = document.createElement("span");
        leftArrow.innerHTML = "&#9664;";
        leftArrow.classList.add("left-arrow");

        let rightArrow = document.createElement("span");
        rightArrow.innerHTML = "&#9654;";
        rightArrow.classList.add("right-arrow");

        let imgElement = document.createElement("img");
        imgElement.classList.add("popup-image");

        let currentIndex = 0;

        function updateImage() {
            imgElement.src = subImages[currentIndex];
        }

        updateImage();

        leftArrow.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateImage();
            }
        });

        rightArrow.addEventListener("click", () => {
            if (currentIndex < subImages.length - 1) {
                currentIndex++;
                updateImage();
            }
        });

        popupContent.appendChild(closeButton);
        popupContent.appendChild(leftArrow);
        popupContent.appendChild(imgElement);
        popupContent.appendChild(rightArrow);
        popupContainer.appendChild(popupContent);
        document.body.appendChild(popupContainer);
    }


});