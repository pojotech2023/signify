document.getElementById("category").addEventListener("change", function () {
    const category = this.value;
    const subCategory = document.getElementById("sub_category");
    const materialContainer = document.getElementById("material-images");
    const materialSection = document.getElementById("material-section");
    const shadesSection = document.getElementById("shades-section");
    const shadeImagesContainer = document.getElementById("shade-images");

    // Clear previous subcategories and material images
    subCategory.innerHTML = '<option selected>Select Sub Category</option>';
    materialContainer.innerHTML = "";
    shadeImagesContainer.innerHTML = "";

    const subCategories = {
        "Metal": ["Stainless Steel", "Aluminium", "Galvanised Iron / SS2B", "Brass"],
        "Acrylic": ["Lit", "Non Lit"]
    };

    const materialImages = {
        "Stainless Steel": [
            { src: "img/metal/m1.jpeg", alt: "SS with Acrylic", title: "SS with Acrylic" },
            { src: "img/metal/m2.jfif", alt: "3D Cuff Non Lit", title: "3D Cuff Non Lit" },
            { src: "img/metal/etching.jpg", alt: "SS Lip Letter", title: "SS Lip Letter" },
            { src: "img/metal/m1.jpeg", alt: "3D Cuff Reverse Lit", title: "3D Cuff Reverse Lit" },
            { src: "img/metal/m1.jpeg", alt: "SS Back lit with acrylic diffuser", title: "SS Back lit with acrylic diffuser" },
            { src: "img/metal/m1.jpeg", alt: "SS 2D Lit", title: "SS 2D Lit" },
            { src: "img/metal/m1.jpeg", alt: "SS with Acrylic Hide lit", title: "SS with Acrylic Hide lit" },
            { src: "img/metal/m1.jpeg", alt: "Etching Plates", title: "Etching Plates" }
        ],
        "Aluminium": [
            { src: "img/metal/etching.jpg", alt: "Die Cast", title: "Die Cast" }
        ],
        "Galvanised Iron / SS2B": [
            { src: "img/metal/m2.jfif", alt: "Cuff Letters", title: "Cuff Letters" },
            { src: "img/metal/m1.jpeg", alt: "Lit Cuff letters", title: "Lit Cuff letters" },
            { src: "img/metal/etching.jpg", alt: "Big Signages", title: "Big Signages" }
        ],
        "Brass": [
            { src: "img/metal/m1.jpeg", alt: "Die Cast Solid letters", title: "Die Cast Solid letters" },
            { src: "img/metal/etching.jpg", alt: "Etching Plates", title: "Etching Plates" }
        ]
    };

    const shades = [
        { src: "img/metal/m1.jpeg", alt: "Silver Mirror", title: "Silver Mirror" },
        { src: "img/metal/m2.jfif", alt: "Silver Brush / Matt", title: "Silver Brush / Matt" },
        { src: "img/metal/etching.jpg", alt: "Titanium Gold Mirror", title: "Titanium Gold Mirror" },
        { src: "img/metal/m1.jpeg", alt: "Matt / Brush Gold", title: "Matt / Brush Gold" },
        { src: "img/metal/m2.jfif", alt: "Rose Gold Mirror", title: "Rose Gold Mirror" },
        { src: "img/metal/etching.jpg", alt: "Rose Gold Brush", title: "Rose Gold Brush" },
        { src: "img/metal/m2.jfif", alt: "Metallic Black", title: "Metallic Black" },
        { src: "img/metal/etching.jpg", alt: "Metallic Green", title: "Metallic Green" },
        { src: "img/metal/etching.jpg", alt: "Metallic Blue", title: "Metallic Blue" }
    ];

    // Populate subcategories
    if (subCategories[category]) {
        subCategories[category].forEach(subCat => {
            const option = document.createElement("option");
            option.value = subCat;
            option.textContent = subCat;
            subCategory.appendChild(option);
        });
        subCategory.disabled = false;
    } else {
        subCategory.disabled = true;
    }

    // Show material images based on subcategory
    subCategory.addEventListener("change", function () {
        const subCategoryValue = this.value;
        materialContainer.innerHTML = "";
        materialSection.style.display = materialImages[subCategoryValue] ? "block" : "none";

        if (materialImages[subCategoryValue]) {
            materialImages[subCategoryValue].forEach(item => {
                const colDiv = document.createElement("div");
                colDiv.className = "col-4";
                colDiv.innerHTML = `
                    <div class="image-container d-flex flex-column align-items-center">
                        <img src="${item.src}" alt="${item.alt}" class="img-fluid selectable-image">
                        <div class="mt-2 text-center title">${item.title}</div>
                    </div>
                `;
                materialContainer.appendChild(colDiv);
            });

            document.querySelectorAll('.selectable-image').forEach(img => {
                img.addEventListener('click', function () {
                    this.classList.toggle('selected');
                });
            });
        }
    });

    // Show shades for Metal category
    shadesSection.style.display = category === "Metal" ? "block" : "none";
    if (category === "Metal") {
        shadeImagesContainer.innerHTML = shades.map(shade => `
            <div class="col-2 p-1">
                <div class="image-container d-flex flex-column align-items-center">
                    <img src="${shade.src}" alt="${shade.alt}" class="img-fluid selectable-image" data-title="${shade.title}">
                    <p class="mt-2 title">${shade.title}</p>
                </div>
            </div>
        `).join("");

        document.querySelectorAll('.selectable-image').forEach(img => {
            img.addEventListener('click', function () {
                this.classList.toggle('selected');
            });
        });
    }

});
