/*
|--------------------------------------------------------------------------
| UrbanNest - Add Property JavaScript
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Current Step
|--------------------------------------------------------------------------
*/

let currentStep = 1;

const totalSteps = 7;


/*
|--------------------------------------------------------------------------
| Elements
|--------------------------------------------------------------------------
*/

const categorySelect =
    document.getElementById("category_id");

const subcategorySelect =
    document.getElementById("subcategory_id");


/*
|--------------------------------------------------------------------------
| Category → Subcategory
|--------------------------------------------------------------------------
*/

categorySelect.addEventListener(
    "change",
    function () {

        const categoryId =
            this.value;


        subcategorySelect.innerHTML =
            "";


        if (
            categoryId === ""
        ) {

            subcategorySelect.disabled =
                true;

            const option =
                document.createElement(
                    "option"
                );

            option.value =
                "";

            option.textContent =
                "-- Select Category First --";

            subcategorySelect.appendChild(
                option
            );

            hideDynamicFields();

            clearAmenities();

            return;

        }


        const defaultOption =
            document.createElement(
                "option"
            );

        defaultOption.value =
            "";

        defaultOption.textContent =
            "-- Select Subcategory --";

        subcategorySelect.appendChild(
            defaultOption
        );


        subcategories.forEach(
            function (subcategory) {

                if (
                    String(
                        subcategory.category_id
                    ) ===
                    String(categoryId)
                ) {

                    const option =
                        document.createElement(
                            "option"
                        );

                    option.value =
                        subcategory.subcategory_id;

                    option.textContent =
                        subcategory.subcategory_name;

                    subcategorySelect.appendChild(
                        option
                    );

                }

            }
        );


        subcategorySelect.disabled =
            false;

    }
);


/*
|--------------------------------------------------------------------------
| Subcategory → Dynamic Details
|--------------------------------------------------------------------------
*/

subcategorySelect.addEventListener(
    "change",
    function () {

        const selectedSubcategoryId =
            this.value;


        const selectedSubcategory =
            subcategories.find(
                function (subcategory) {

                    return String(
                        subcategory.subcategory_id
                    ) ===
                    String(
                        selectedSubcategoryId
                    );

                }
            );


        if (
            !selectedSubcategory
        ) {

            hideDynamicFields();

            clearAmenities();

            return;

        }


        const subcategoryId =
            selectedSubcategory.subcategory_id;

        const subcategoryName =
            selectedSubcategory.subcategory_name.trim();


        showDynamicFields(
            subcategoryId,
            subcategoryName
        );


        showAmenities(
            subcategoryId
        );

    }
);


/*
|--------------------------------------------------------------------------
| Property Field Configuration
|--------------------------------------------------------------------------
*/

const propertyFieldConfig = {

    // Residential
    1: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    2: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    3: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    4: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    5: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],


    // Commercial
    6: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    7: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    8: [
        "total-floors"
    ],

    9: [
        "furnishing",
        "floor-number",
        "total-floors"
    ],


    // Luxury
    10: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "floor-number",
        "total-floors"
    ],

    11: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    12: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],

    13: [
        "bedrooms",
        "bathrooms",
        "furnishing",
        "total-floors"
    ],


    // Land
    14: [
        "plot-length",
        "plot-width"
    ],

    15: [
        "plot-length",
        "plot-width"
    ],

    16: [
        "plot-length",
        "plot-width"
    ],


    // Industrial
    17: [
        "total-floors"
    ],

    18: [
        "total-floors"
    ]

};


/*
|--------------------------------------------------------------------------
| Show Dynamic Details
|--------------------------------------------------------------------------
*/

function showDynamicFields(
    subcategoryId,
    subcategoryName
) {

    const dynamicDetails =
        document.getElementById(
            "dynamic-details"
        );


    hideDynamicFields();


    const fields =
        propertyFieldConfig[
            subcategoryId
        ];


    if (
        !fields ||
        fields.length === 0
    ) {

        return;

    }


    dynamicDetails.style.display =
        "block";


    fields.forEach(
        function (fieldName) {

            const field =
                document.getElementById(
                    "field-" + fieldName
                );


            if (field) {

                field.style.display =
                    "block";

            }

        }
    );


    const title =
        document.getElementById(
            "dynamic-details-title"
        );


    const description =
        document.getElementById(
            "dynamic-details-description"
        );


    title.textContent =
        subcategoryName +
        " Specifications";


    description.textContent =
        "Enter the specifications for this " +
        subcategoryName.toLowerCase() +
        ".";

}


/*
|--------------------------------------------------------------------------
| Hide Dynamic Details
|--------------------------------------------------------------------------
*/

function hideDynamicFields() {

    const dynamicDetails =
        document.getElementById(
            "dynamic-details"
        );


    dynamicDetails.style.display =
        "none";


    const fields =
        document.querySelectorAll(
            ".dynamic-field"
        );


    fields.forEach(
        function (field) {

            field.style.display =
                "none";

        }
    );


    const inputs =
        dynamicDetails.querySelectorAll(
            "input, select"
        );


    inputs.forEach(
        function (input) {

            input.value =
                "";

        }
    );

}


/*
|--------------------------------------------------------------------------
| Amenity Configuration
|--------------------------------------------------------------------------
*/

const amenityConfig = {

    // Apartment
    1: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Villa
    2: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Bungalow
    3: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Duplex
    4: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Independent House
    5: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Gated Community",
        "Pet Friendly"
    ],

    // Office
    6: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Air Conditioning",
        "EV Charging",
        "Gated Community"
    ],

    // Shop
    7: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Air Conditioning",
        "Gated Community"
    ],

    // Warehouse
    8: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Gated Community"
    ],

    // Showroom
    9: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Air Conditioning",
        "EV Charging",
        "Gated Community"
    ],

    // Penthouse
    10: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Lift",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Farmhouse
    11: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Gated Community",
        "Pet Friendly"
    ],

    // Luxury Villa
    12: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "EV Charging",
        "Gated Community",
        "Pet Friendly"
    ],

    // Luxury Bungalow
    13: [
        "Parking",
        "Swimming Pool",
        "Private Gym",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "Balcony",
        "Air Conditioning",
        "Smart Home",
        "Clubhouse",
        "Gated Community",
        "Pet Friendly"
    ],

    // Residential Plot
    14: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Commercial Plot
    15: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Agriculture
    16: [
        "24/7 Security",
        "CCTV Surveillance",
        "Gated Community"
    ],

    // Factory
    17: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "EV Charging",
        "Gated Community"
    ],

    // Industrial Shed
    18: [
        "Parking",
        "24/7 Security",
        "CCTV Surveillance",
        "Power Backup",
        "EV Charging",
        "Gated Community"
    ]

};


/*
|--------------------------------------------------------------------------
| Show Amenities
|--------------------------------------------------------------------------
*/

function showAmenities(
    subcategoryId
) {

    const container =
        document.getElementById(
            "amenities-container"
        );


    const message =
        document.getElementById(
            "amenities-message"
        );


    container.innerHTML =
        "";


    const allowedAmenities =
        amenityConfig[
            subcategoryId
        ];


    if (
        !allowedAmenities ||
        allowedAmenities.length === 0
    ) {

        message.textContent =
            "No predefined amenities available.";

        container.appendChild(
            message
        );

        return;

    }


    amenities.forEach(
        function (amenity) {

            if (
                allowedAmenities.includes(
                    amenity.amentiy_name
                )
            ) {

                const wrapper =
                    document.createElement(
                        "div"
                    );

                wrapper.className =
                    "amenity-option";


                const checkbox =
                    document.createElement(
                        "input"
                    );

                checkbox.type =
                    "checkbox";

                checkbox.name =
                    "amenities[]";

                checkbox.value =
                    amenity.amentiy_id;

                checkbox.id =
                    "amenity-" +
                    amenity.amentiy_id;


                const label =
                    document.createElement(
                        "label"
                    );

                label.htmlFor =
                    checkbox.id;

                label.textContent =
                    amenity.amentiy_name;


                wrapper.appendChild(
                    checkbox
                );

                wrapper.appendChild(
                    label
                );


                container.appendChild(
                    wrapper
                );

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Clear Amenities
|--------------------------------------------------------------------------
*/

function clearAmenities() {

    const container =
        document.getElementById(
            "amenities-container"
        );


    container.innerHTML =
        "";


    const message =
        document.createElement(
            "p"
        );


    message.id =
        "amenities-message";


    message.textContent =
        "Select a property type first to see available amenities.";


    container.appendChild(
        message
    );

}


/*
|--------------------------------------------------------------------------
| Show Step
|--------------------------------------------------------------------------
*/

function showStep(
    stepNumber
) {

    const steps =
        document.querySelectorAll(
            ".property-step"
        );


    const indicators =
        document.querySelectorAll(
            ".property-stepper .step"
        );


    steps.forEach(
        function (step) {

            step.classList.remove(
                "active"
            );

        }
    );


    const currentSection =
        document.getElementById(
            "step-" + stepNumber
        );


    if (currentSection) {

        currentSection.classList.add(
            "active"
        );

    }


    indicators.forEach(
        function (
            indicator,
            index
        ) {

            indicator.classList.remove(
                "active",
                "completed"
            );


            if (
                index + 1 ===
                stepNumber
            ) {

                indicator.classList.add(
                    "active"
                );

            }


            if (
                index + 1 <
                stepNumber
            ) {

                indicator.classList.add(
                    "completed"
                );

            }

        }
    );


    currentStep =
        stepNumber;


    if (
        stepNumber === 7
    ) {

        buildPropertyReview();

    }


    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/*
|--------------------------------------------------------------------------
| Validate Current Step
|--------------------------------------------------------------------------
*/

function validateCurrentStep() {

    /*
    |--------------------------------------------------------------------------
    | STEP 1 — OWNER
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 1
    ) {

        const owner =
            document.getElementById(
                "user_id"
            );


        if (
            !owner.value
        ) {

            alert(
                "Please select a property owner."
            );

            owner.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2 — TYPE
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 2
    ) {

        const category =
            document.getElementById(
                "category_id"
            );


        const subcategory =
            document.getElementById(
                "subcategory_id"
            );


        if (
            !category.value
        ) {

            alert(
                "Please select a category."
            );

            category.focus();

            return false;

        }


        if (
            !subcategory.value
        ) {

            alert(
                "Please select a subcategory."
            );

            subcategory.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 3 — DETAILS
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 3
    ) {

        const title =
            document.getElementById(
                "title"
            );


        const description =
            document.getElementById(
                "description"
            );


        const listingType =
            document.getElementById(
                "listing_type"
            );


        const price =
            document.getElementById(
                "price"
            );


        const area =
            document.getElementById(
                "area_sqft"
            );


        if (
            !title.value.trim()
        ) {

            alert(
                "Please enter a property title."
            );

            title.focus();

            return false;

        }


        if (
            !description.value.trim()
        ) {

            alert(
                "Please enter a property description."
            );

            description.focus();

            return false;

        }


        if (
            !listingType.value
        ) {

            alert(
                "Please select a listing type."
            );

            listingType.focus();

            return false;

        }


        if (
            !price.value
        ) {

            alert(
                "Please enter the property price."
            );

            price.focus();

            return false;

        }


        if (
            !area.value
        ) {

            alert(
                "Please enter the property area."
            );

            area.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 4 — LOCATION
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 4
    ) {

        const address =
            document.getElementById(
                "address"
            );


        const city =
            document.getElementById(
                "city"
            );


        const state =
            document.getElementById(
                "state"
            );


        if (
            !address.value.trim()
        ) {

            alert(
                "Please enter the property address."
            );

            address.focus();

            return false;

        }


        if (
            !city.value.trim()
        ) {

            alert(
                "Please enter the city."
            );

            city.focus();

            return false;

        }


        if (
            !state.value.trim()
        ) {

            alert(
                "Please enter the state."
            );

            state.focus();

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 5 — AMENITIES
    |--------------------------------------------------------------------------
    */

    if (
        currentStep === 5
    ) {

        /*
        |--------------------------------------------------------------------------
        | Amenities are optional.
        |--------------------------------------------------------------------------
        |
        | A property does not have to have every amenity.
        | "Other Amenities" can also be used.
        |
        |--------------------------------------------------------------------------
        */

    }


    return true;

}


/*
|--------------------------------------------------------------------------
| Next Step
|--------------------------------------------------------------------------
*/

function nextStep() {

    if (
        !validateCurrentStep()
    ) {

        return;

    }


    if (
        currentStep <
        totalSteps
    ) {

        showStep(
            currentStep + 1
        );

    }

}


/*
|--------------------------------------------------------------------------
| Previous Step
|--------------------------------------------------------------------------
*/

function previousStep() {

    if (
        currentStep > 1
    ) {

        showStep(
            currentStep - 1
        );

    }

}


/*
|--------------------------------------------------------------------------
| Property Photo Preview
|--------------------------------------------------------------------------
*/

const photoInput =
    document.getElementById(
        "property_photos"
    );

const photoPreview =
    document.getElementById(
        "photo-preview"
    );


let selectedPhotos = [];


/*
|--------------------------------------------------------------------------
| Photo Selection
|--------------------------------------------------------------------------
*/

photoInput.addEventListener(
    "change",
    function () {

        const newPhotos =
            Array.from(
                this.files
            );


        newPhotos.forEach(
            function (file) {

                const alreadyExists =
                    selectedPhotos.some(
                        function (existingFile) {

                            return (
                                existingFile.name === file.name &&
                                existingFile.size === file.size &&
                                existingFile.lastModified === file.lastModified
                            );

                        }
                    );


                if (
                    !alreadyExists
                ) {

                    selectedPhotos.push(
                        file
                    );

                }

            }
        );


        displayPhotoPreviews();


        /*
        |--------------------------------------------------------------------------
        | Reset input
        |--------------------------------------------------------------------------
        */

        this.value = "";

    }
);


/*
|--------------------------------------------------------------------------
| Display Photo Previews
|--------------------------------------------------------------------------
*/

function displayPhotoPreviews() {

    photoPreview.innerHTML =
        "";


    selectedPhotos.forEach(
        function (
            file,
            index
        ) {

            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    const photoBox =
                        document.createElement(
                            "div"
                        );

                    photoBox.className =
                        "photo-preview-item";


                    const image =
                        document.createElement(
                            "img"
                        );

                    image.src =
                        event.target.result;


                    const removeButton =
                        document.createElement(
                            "button"
                        );

                    removeButton.type =
                        "button";

                    removeButton.className =
                        "remove-photo";

                    removeButton.textContent =
                        "×";


                    removeButton.onclick =
                        function () {

                            removePhoto(
                                index
                            );

                        };


                    photoBox.appendChild(
                        image
                    );

                    photoBox.appendChild(
                        removeButton
                    );


                    photoPreview.appendChild(
                        photoBox
                    );

                };


            reader.readAsDataURL(
                file
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Remove Photo
|--------------------------------------------------------------------------
*/

function removePhoto(
    index
) {

    selectedPhotos.splice(
        index,
        1
    );


    displayPhotoPreviews();


    const dataTransfer =
        new DataTransfer();


    selectedPhotos.forEach(
        function (file) {

            dataTransfer.items.add(
                file
            );

        }
    );


    photoInput.files =
        dataTransfer.files;

}


/*
|--------------------------------------------------------------------------
| Build Property Review
|--------------------------------------------------------------------------
*/

function buildPropertyReview() {

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    const ownerSelect =
        document.getElementById(
            "user_id"
        );

    const ownerText =
        ownerSelect.options[
            ownerSelect.selectedIndex
        ]?.text || "—";

    document.getElementById(
        "review-owner"
    ).textContent =
        ownerText;


    /*
    |--------------------------------------------------------------------------
    | Property Type
    |--------------------------------------------------------------------------
    */

    const subcategorySelect =
        document.getElementById(
            "subcategory_id"
        );

    const typeText =
        subcategorySelect.options[
            subcategorySelect.selectedIndex
        ]?.text || "—";

    document.getElementById(
        "review-type"
    ).textContent =
        typeText;


    /*
    |--------------------------------------------------------------------------
    | Basic Details
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        "review-title"
    ).textContent =
        document.getElementById(
            "title"
        ).value || "—";


    document.getElementById(
        "review-listing"
    ).textContent =
        document.getElementById(
            "listing_type"
        ).value || "—";


    document.getElementById(
        "review-price"
    ).textContent =
        document.getElementById(
            "price"
        ).value
            ? "₹ " +
              Number(
                  document.getElementById(
                      "price"
                  ).value
              ).toLocaleString(
                  "en-IN"
              )
            : "—";


    document.getElementById(
        "review-area"
    ).textContent =
        document.getElementById(
            "area_sqft"
        ).value
            ? document.getElementById(
                  "area_sqft"
              ).value +
              " sq.ft."
            : "—";


    /*
    |--------------------------------------------------------------------------
    | Property Condition
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        "review-condition"
    ).textContent =
        document.getElementById(
            "property_condition"
        ).value || "—";


    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        "review-description"
    ).textContent =
        document.getElementById(
            "description"
        ).value || "—";


    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        "review-address"
    ).textContent =
        document.getElementById(
            "address"
        ).value || "—";


    document.getElementById(
        "review-city"
    ).textContent =
        document.getElementById(
            "city"
        ).value || "—";


    document.getElementById(
        "review-state"
    ).textContent =
        document.getElementById(
            "state"
        ).value || "—";


    /*
    |--------------------------------------------------------------------------
    | Specifications
    |--------------------------------------------------------------------------
    */

    const specifications =
        document.getElementById(
            "review-specifications"
        );

    specifications.innerHTML =
        "";


    const specificationFields = [

        {
            id: "bedrooms",
            label: "Bedrooms"
        },

        {
            id: "bathrooms",
            label: "Bathrooms"
        },

        {
            id: "furnishing",
            label: "Furnishing"
        },

        {
            id: "floor_number",
            label: "Floor Number"
        },

        {
            id: "total_floors",
            label: "Total Floors"
        },

        {
            id: "plot_length",
            label: "Plot Length"
        },

        {
            id: "plot_width",
            label: "Plot Width"
        }

    ];


    specificationFields.forEach(
        function (field) {

            const element =
                document.getElementById(
                    field.id
                );


            if (
                !element ||
                !element.value
            ) {

                return;

            }


            const item =
                document.createElement(
                    "div"
                );

            item.className =
                "review-item";


            const label =
                document.createElement(
                    "span"
                );

            label.textContent =
                field.label;


            const value =
                document.createElement(
                    "strong"
                );

            value.textContent =
                element.value;


            item.appendChild(
                label
            );

            item.appendChild(
                value
            );


            specifications.appendChild(
                item
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Amenities
    |--------------------------------------------------------------------------
    */

    const reviewAmenities =
        document.getElementById(
            "review-amenities"
        );


    reviewAmenities.innerHTML =
        "";


    const selectedAmenities =
        document.querySelectorAll(
            '#amenities-container input[name="amenities[]"]:checked'
        );


    if (
        selectedAmenities.length === 0
    ) {

        reviewAmenities.textContent =
            "No predefined amenities selected.";

    } else {

        selectedAmenities.forEach(
            function (checkbox) {

                const label =
                    document.querySelector(
                        'label[for="' +
                        checkbox.id +
                        '"]'
                    );


                if (
                    !label
                ) {

                    return;

                }


                const tag =
                    document.createElement(
                        "span"
                    );

                tag.className =
                    "review-tag";

                tag.textContent =
                    label.textContent;

                reviewAmenities.appendChild(
                    tag
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Other Amenities
    |--------------------------------------------------------------------------
    */

    const otherAmenities =
        document.getElementById(
            "other_amenities"
        ).value.trim();


    const otherReview =
        document.getElementById(
            "review-other-amenities"
        );


    otherReview.innerHTML =
        "";


    if (
        otherAmenities
    ) {

        const label =
            document.createElement(
                "strong"
            );

        label.textContent =
            "Other Amenities: ";


        const text =
            document.createElement(
                "span"
            );

        text.textContent =
            otherAmenities;


        otherReview.appendChild(
            label
        );

        otherReview.appendChild(
            text
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Photos
    |--------------------------------------------------------------------------
    */

    const reviewPhotos =
        document.getElementById(
            "review-photos"
        );


    reviewPhotos.innerHTML =
        "";


    if (
        selectedPhotos.length === 0
    ) {

        reviewPhotos.textContent =
            "No photos selected.";

        return;

    }


    selectedPhotos.forEach(
        function (file) {

            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    const image =
                        document.createElement(
                            "img"
                        );

                    image.src =
                        event.target.result;

                    image.alt =
                        "Property Photo";


                    reviewPhotos.appendChild(
                        image
                    );

                };


            reader.readAsDataURL(
                file
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Start
|--------------------------------------------------------------------------
*/

showStep(1);