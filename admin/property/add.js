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
|
| These names must match the amenities table.
|
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


    /*
    |--------------------------------------------------------------------------
    | Clear Previous Amenities
    |--------------------------------------------------------------------------
    */

    container.innerHTML =
        "";


    /*
    |--------------------------------------------------------------------------
    | Get Allowed Amenities
    |--------------------------------------------------------------------------
    */

    const allowedAmenities =
        amenityConfig[
            subcategoryId
        ];


    /*
    |--------------------------------------------------------------------------
    | No Configuration
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Create Amenity Checkboxes
    |--------------------------------------------------------------------------
    */

    amenities.forEach(
        function (amenity) {

            if (
                allowedAmenities.includes(
                    amenity.amenity_name
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
                    amenity.amenity_id;

                checkbox.id =
                    "amenity-" +
                    amenity.amenity_id;


                const label =
                    document.createElement(
                        "label"
                    );

                label.htmlFor =
                    checkbox.id;

                label.textContent =
                    amenity.amenity_name;


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
                "Please enter the property description."
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
    |
    | Amenities are optional.
    |
    */

    if (
        currentStep === 5
    ) {

        /*
        | No validation required.
        */

    }


    /*
    |--------------------------------------------------------------------------
    | STEP 6 — PHOTOS
    |--------------------------------------------------------------------------
    |
    | A main photo must be explicitly chosen. It is never inferred
    | automatically from the additional photos.
    |
    */

    if (
        currentStep === 6
    ) {

        if (
            !selectedMainPhoto
        ) {

            alert(
                "Please select a main property photo."
            );

            return false;

        }

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
| Main Property Photo Preview
|--------------------------------------------------------------------------
*/

const mainPhotoInput =
    document.getElementById(
        "main_property_photo"
    );

const mainPhotoPreview =
    document.getElementById(
        "main-photo-preview"
    );


let selectedMainPhoto = null;


/*
|--------------------------------------------------------------------------
| Main Photo Selection
|--------------------------------------------------------------------------
*/

if (
    mainPhotoInput
) {

    mainPhotoInput.addEventListener(
        "change",
        function () {

            const file =
                this.files &&
                this.files[0]
                    ? this.files[0]
                    : null;


            if (
                file
            ) {

                selectedMainPhoto =
                    file;

            }


            displayMainPhotoPreview();

        }
    );

}


/*
|--------------------------------------------------------------------------
| Display Main Photo Preview
|--------------------------------------------------------------------------
*/

function displayMainPhotoPreview() {

    if (
        !mainPhotoPreview
    ) {

        return;

    }


    mainPhotoPreview.innerHTML =
        "";


    if (
        !selectedMainPhoto
    ) {

        return;

    }


    const reader =
        new FileReader();


    reader.onload =
        function (event) {

            const photoBox =
                document.createElement(
                    "div"
                );

            photoBox.className =
                "photo-preview-item main-photo-preview-item";


            const image =
                document.createElement(
                    "img"
                );

            image.src =
                event.target.result;


            const badge =
                document.createElement(
                    "span"
                );

            badge.className =
                "main-photo-badge";

            badge.textContent =
                "MAIN";


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

                    removeMainPhoto();

                };


            photoBox.appendChild(
                image
            );

            photoBox.appendChild(
                badge
            );

            photoBox.appendChild(
                removeButton
            );


            mainPhotoPreview.appendChild(
                photoBox
            );

        };


    reader.readAsDataURL(
        selectedMainPhoto
    );

}


/*
|--------------------------------------------------------------------------
| Remove Main Photo
|--------------------------------------------------------------------------
*/

function removeMainPhoto() {

    selectedMainPhoto =
        null;


    if (
        mainPhotoInput
    ) {

        mainPhotoInput.value =
            "";

    }


    displayMainPhotoPreview();

}


/*
|--------------------------------------------------------------------------
| Property Photo Preview (Additional Photos)
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

if (
    photoInput
) {

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
                                    existingFile.name ===
                                    file.name &&
                                    existingFile.size ===
                                    file.size &&
                                    existingFile.lastModified ===
                                    file.lastModified
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
            | Re-sync Input With Accumulated Selection
            |--------------------------------------------------------------------------
            |
            | Resetting with this.value = "" also empties the FileList that
            | gets submitted with the form, so only the last batch (or
            | nothing) would reach PHP. Rebuilding the input's files from
            | the full selectedPhotos array keeps every batch attached to
            | property_photos[] while still allowing another batch to be
            | picked afterward.
            |--------------------------------------------------------------------------
            */

            const dataTransfer =
                new DataTransfer();


            selectedPhotos.forEach(
                function (file) {

                    dataTransfer.items.add(
                        file
                    );

                }
            );


            this.files =
                dataTransfer.files;

        }
    );

}


/*
|--------------------------------------------------------------------------
| Display Photo Previews
|--------------------------------------------------------------------------
*/

function displayPhotoPreviews() {

    if (
        !photoPreview
    ) {

        return;

    }


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


    if (
        !photoInput
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Update File Input
    |--------------------------------------------------------------------------
    */

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
| Photo Count Helper
|--------------------------------------------------------------------------
*/

function getSelectedPhotoCount() {

    return selectedPhotos.length;

}


/*
|--------------------------------------------------------------------------
| Clear Selected Photos
|--------------------------------------------------------------------------
*/

function clearSelectedPhotos() {

    selectedPhotos = [];


    if (
        photoInput
    ) {

        photoInput.value =
            "";

    }


    displayPhotoPreviews();

}


/*
|--------------------------------------------------------------------------
| Property Review — Basic Information
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
        ]?.text ||
        "—";


    const reviewOwner =
        document.getElementById(
            "review-owner"
        );


    if (
        reviewOwner
    ) {

        reviewOwner.textContent =
            ownerText;

    }


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
        ]?.text ||
        "—";


    const reviewType =
        document.getElementById(
            "review-type"
        );


    if (
        reviewType
    ) {

        reviewType.textContent =
            typeText;

    }


    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    const reviewTitle =
        document.getElementById(
            "review-title"
        );


    if (
        reviewTitle
    ) {

        reviewTitle.textContent =
            document.getElementById(
                "title"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Listing Type
    |--------------------------------------------------------------------------
    */

    const reviewListing =
        document.getElementById(
            "review-listing"
        );


    if (
        reviewListing
    ) {

        reviewListing.textContent =
            document.getElementById(
                "listing_type"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Price
    |--------------------------------------------------------------------------
    */

    const reviewPrice =
        document.getElementById(
            "review-price"
        );


    if (
        reviewPrice
    ) {

        const price =
            document.getElementById(
                "price"
            ).value;


        reviewPrice.textContent =
            price
                ? "₹ " +
                  Number(
                      price
                  ).toLocaleString(
                      "en-IN"
                  )
                : "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Area
    |--------------------------------------------------------------------------
    */

    const reviewArea =
        document.getElementById(
            "review-area"
        );


    if (
        reviewArea
    ) {

        const area =
            document.getElementById(
                "area_sqft"
            ).value;


        reviewArea.textContent =
            area
                ? area +
                  " sq.ft."
                : "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Condition
    |--------------------------------------------------------------------------
    */

    const reviewCondition =
        document.getElementById(
            "review-condition"
        );


    if (
        reviewCondition
    ) {

        reviewCondition.textContent =
            document.getElementById(
                "property_condition"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    const reviewDescription =
        document.getElementById(
            "review-description"
        );


    if (
        reviewDescription
    ) {

        reviewDescription.textContent =
            document.getElementById(
                "description"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    const reviewAddress =
        document.getElementById(
            "review-address"
        );


    if (
        reviewAddress
    ) {

        reviewAddress.textContent =
            document.getElementById(
                "address"
            ).value ||
            "—";

    }


    const reviewCity =
        document.getElementById(
            "review-city"
        );


    if (
        reviewCity
    ) {

        reviewCity.textContent =
            document.getElementById(
                "city"
            ).value ||
            "—";

    }


    const reviewState =
        document.getElementById(
            "review-state"
        );


    if (
        reviewState
    ) {

        reviewState.textContent =
            document.getElementById(
                "state"
            ).value ||
            "—";

    }


    /*
    |--------------------------------------------------------------------------
    | Specifications
    |--------------------------------------------------------------------------
    */

    buildSpecificationReview();


    /*
    |--------------------------------------------------------------------------
    | Amenities
    |--------------------------------------------------------------------------
    */

    buildAmenityReview();


    /*
    |--------------------------------------------------------------------------
    | Other Amenities
    |--------------------------------------------------------------------------
    */

    buildOtherAmenityReview();


    /*
    |--------------------------------------------------------------------------
    | Photos
    |--------------------------------------------------------------------------
    */

    buildPhotoReview();

}


/*
|--------------------------------------------------------------------------
| Specification Review
|--------------------------------------------------------------------------
*/

function buildSpecificationReview() {

    const specifications =
        document.getElementById(
            "review-specifications"
        );


    if (
        !specifications
    ) {

        return;

    }


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

}


/*
|--------------------------------------------------------------------------
| Amenity Review
|--------------------------------------------------------------------------
*/

function buildAmenityReview() {

    const reviewAmenities =
        document.getElementById(
            "review-amenities"
        );


    if (
        !reviewAmenities
    ) {

        return;

    }


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

        return;

    }


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
| Other Amenities Review
|--------------------------------------------------------------------------
*/

function buildOtherAmenityReview() {

    const otherInput =
        document.getElementById(
            "other_amenities"
        );


    const reviewOther =
        document.getElementById(
            "review-other-amenities"
        );


    if (
        !reviewOther
    ) {

        return;

    }


    reviewOther.innerHTML =
        "";


    if (
        !otherInput
    ) {

        return;

    }


    const otherAmenities =
        otherInput.value.trim();


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


        reviewOther.appendChild(
            label
        );


        reviewOther.appendChild(
            text
        );

    }

}


/*
|--------------------------------------------------------------------------
| Photo Review
|--------------------------------------------------------------------------
*/

function buildPhotoReview() {

    const reviewPhotos =
        document.getElementById(
            "review-photos"
        );


    if (
        !reviewPhotos
    ) {

        return;

    }


    reviewPhotos.innerHTML =
        "";


    if (
        !selectedMainPhoto &&
        selectedPhotos.length === 0
    ) {

        reviewPhotos.textContent =
            "No photos selected.";

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Main Photo
    |--------------------------------------------------------------------------
    */

    if (
        selectedMainPhoto
    ) {

        const mainReader =
            new FileReader();


        mainReader.onload =
            function (event) {

                const wrapper =
                    document.createElement(
                        "div"
                    );

                wrapper.className =
                    "main-photo-preview-item";


                const image =
                    document.createElement(
                        "img"
                    );

                image.src =
                    event.target.result;

                image.alt =
                    "Main Property Photo";


                const badge =
                    document.createElement(
                        "span"
                    );

                badge.className =
                    "main-photo-badge";

                badge.textContent =
                    "MAIN";


                wrapper.appendChild(
                    image
                );

                wrapper.appendChild(
                    badge
                );


                reviewPhotos.appendChild(
                    wrapper
                );

            };


        mainReader.readAsDataURL(
            selectedMainPhoto
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Additional Photos
    |--------------------------------------------------------------------------
    */

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
| Review Refresh Helper
|--------------------------------------------------------------------------
*/

function refreshPropertyReview() {

    if (
        currentStep === 7
    ) {

        buildPropertyReview();

    }

}


/*
|--------------------------------------------------------------------------
| Review Data Helpers
|--------------------------------------------------------------------------
*/

function getInputValue(
    elementId
) {

    const element =
        document.getElementById(
            elementId
        );


    if (
        !element
    ) {

        return "";

    }


    return element.value;

}


/*
|--------------------------------------------------------------------------
| Selected Subcategory Helper
|--------------------------------------------------------------------------
*/

function getSelectedSubcategory() {

    const select =
        document.getElementById(
            "subcategory_id"
        );


    if (
        !select ||
        !select.value
    ) {

        return null;

    }


    return subcategories.find(
        function (subcategory) {

            return String(
                subcategory.subcategory_id
            ) ===
            String(
                select.value
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| Selected Category Helper
|--------------------------------------------------------------------------
*/

function getSelectedCategory() {

    const select =
        document.getElementById(
            "category_id"
        );


    if (
        !select ||
        !select.value
    ) {

        return null;

    }


    return categories.find(
        function (category) {

            return String(
                category.category_id
            ) ===
            String(
                select.value
            );

        }
    ) || null;

}


/*
|--------------------------------------------------------------------------
| Scroll To Top
|--------------------------------------------------------------------------
*/

function scrollToTop() {

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/*
|--------------------------------------------------------------------------
| Step Indicator Helper
|--------------------------------------------------------------------------
*/

function updateStepIndicators(
    stepNumber
) {

    const indicators =
        document.querySelectorAll(
            ".property-stepper .step"
        );


    indicators.forEach(
        function (
            indicator,
            index
        ) {

            const number =
                index + 1;


            indicator.classList.remove(
                "active",
                "completed"
            );


            if (
                number ===
                stepNumber
            ) {

                indicator.classList.add(
                    "active"
                );

            }


            if (
                number <
                stepNumber
            ) {

                indicator.classList.add(
                    "completed"
                );

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Direct Step Navigation
|--------------------------------------------------------------------------
*/

function goToStep(
    stepNumber
) {

    if (
        stepNumber < 1 ||
        stepNumber > totalSteps
    ) {

        return;

    }


    showStep(
        stepNumber
    );

}


/*
|--------------------------------------------------------------------------
| Step Validation Helper
|--------------------------------------------------------------------------
*/

function isStepValid(
    stepNumber
) {

    const originalStep =
        currentStep;


    currentStep =
        stepNumber;


    const valid =
        validateCurrentStep();


    currentStep =
        originalStep;


    return valid;

}


/*
|--------------------------------------------------------------------------
| Form Submit Protection
|--------------------------------------------------------------------------
*/

const propertyForm =
    document.getElementById(
        "propertyForm"
    );


if (
    propertyForm
) {

    propertyForm.addEventListener(
        "submit",
        function (event) {

            /*
            |--------------------------------------------------------------------------
            | Make sure the form is complete enough before submission.
            |--------------------------------------------------------------------------
            */

            const owner =
                document.getElementById(
                    "user_id"
                );


            const subcategory =
                document.getElementById(
                    "subcategory_id"
                );


            if (
                !owner ||
                !owner.value
            ) {

                event.preventDefault();


                alert(
                    "Please select a property owner."
                );


                showStep(1);


                return;

            }


            if (
                !subcategory ||
                !subcategory.value
            ) {

                event.preventDefault();


                alert(
                    "Please select a property subcategory."
                );


                showStep(2);


                return;

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| Dynamic Details — Preserve Selected Values
|--------------------------------------------------------------------------
*/

function refreshDynamicDetails() {

    const selectedSubcategory =
        getSelectedSubcategory();


    if (
        !selectedSubcategory
    ) {

        hideDynamicFields();

        clearAmenities();

        return;

    }


    showDynamicFields(
        selectedSubcategory.subcategory_id,
        selectedSubcategory.subcategory_name
    );


    showAmenities(
        selectedSubcategory.subcategory_id
    );

}


/*
|--------------------------------------------------------------------------
| Amenity Search / Matching Helper
|--------------------------------------------------------------------------
|
| This does not alter database values.
| It only makes matching safer when capitalization or spaces differ.
|--------------------------------------------------------------------------
*/

function normalizeAmenityName(
    name
) {

    return String(
        name ?? ""
    )
        .trim()
        .toLowerCase();

}


/*
|--------------------------------------------------------------------------
| Improved Amenity Renderer
|--------------------------------------------------------------------------
|
| Kept as a separate function so the existing showAmenities()
| remains compatible with the current form.
|--------------------------------------------------------------------------
*/

function renderAmenitiesForSubcategory(
    subcategoryId
) {

    const container =
        document.getElementById(
            "amenities-container"
        );


    if (
        !container
    ) {

        return;

    }


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

        const message =
            document.createElement(
                "p"
            );


        message.id =
            "amenities-message";


        message.textContent =
            "No predefined amenities available.";


        container.appendChild(
            message
        );


        return;

    }


    const normalizedAllowed =
        allowedAmenities.map(
            function (name) {

                return normalizeAmenityName(
                    name
                );

            }
        );


    let matchFound =
        false;


    amenities.forEach(
        function (amenity) {

            const amenityName =
                normalizeAmenityName(
                    amenity.amenity_name
                );


            if (
                !normalizedAllowed.includes(
                    amenityName
                )
            ) {

                return;

            }


            matchFound =
                true;


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
                amenity.amenity_id;


            checkbox.id =
                "amenity-" +
                amenity.amenity_id;


            const label =
                document.createElement(
                    "label"
                );


            label.htmlFor =
                checkbox.id;


            label.textContent =
                amenity.amenity_name;


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
    );


    if (
        !matchFound
    ) {

        const message =
            document.createElement(
                "p"
            );


        message.id =
            "amenities-message";


        message.textContent =
            "No predefined amenities available for this property type.";


        container.appendChild(
            message
        );

    }

}


/*
|--------------------------------------------------------------------------
| Re-render Amenities When Needed
|--------------------------------------------------------------------------
*/

function refreshAmenities() {

    const selectedSubcategory =
        getSelectedSubcategory();


    if (
        !selectedSubcategory
    ) {

        clearAmenities();

        return;

    }


    renderAmenitiesForSubcategory(
        selectedSubcategory.subcategory_id
    );

}


/*
|--------------------------------------------------------------------------
| Review Edit Helpers
|--------------------------------------------------------------------------
|
| These are ready for the V2 Edit buttons.
| They do not change the current V1 UI.
|--------------------------------------------------------------------------
*/

function editOwnerStep() {

    showStep(1);

}


function editTypeStep() {

    showStep(2);

}


function editDetailsStep() {

    showStep(3);

}


function editLocationStep() {

    showStep(4);

}


function editAmenitiesStep() {

    showStep(5);

}


function editPhotosStep() {

    showStep(6);

}


/*
|--------------------------------------------------------------------------
| Keyboard Safety
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "keydown",
    function (event) {

        /*
        |--------------------------------------------------------------------------
        | Prevent accidental Enter submission while editing fields.
        |--------------------------------------------------------------------------
        */

        if (
            event.key !== "Enter"
        ) {

            return;

        }


        const target =
            event.target;


        if (
            !target
        ) {

            return;

        }


        const tagName =
            target.tagName
                .toLowerCase();


        if (
            tagName === "textarea"
        ) {

            return;

        }


        if (
            tagName === "input" ||
            tagName === "select"
        ) {

            event.preventDefault();

        }

    }
);


/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
        |--------------------------------------------------------------------------
        | Make sure Step 1 is displayed.
        |--------------------------------------------------------------------------
        */

        showStep(1);


        /*
        |--------------------------------------------------------------------------
        | Make sure the subcategory is initially disabled.
        |--------------------------------------------------------------------------
        */

        if (
            subcategorySelect
        ) {

            subcategorySelect.disabled =
                true;

        }


        /*
        |--------------------------------------------------------------------------
        | Clear dynamic sections.
        |--------------------------------------------------------------------------
        */

        hideDynamicFields();

        clearAmenities();

    }
);
/*
|--------------------------------------------------------------------------
| Final Initialization
|--------------------------------------------------------------------------
|
| Keep the form in a clean initial state when the page loads.
|--------------------------------------------------------------------------
*/

(function initializePropertyForm() {

    /*
    |--------------------------------------------------------------------------
    | Category / Subcategory
    |--------------------------------------------------------------------------
    */

    if (
        categorySelect
    ) {

        /*
        | If a category is already selected by PHP,
        | populate its subcategories.
        */

        if (
            categorySelect.value
        ) {

            categorySelect.dispatchEvent(
                new Event("change")
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Step
    |--------------------------------------------------------------------------
    */

    showStep(1);


    /*
    |--------------------------------------------------------------------------
    | Initial Dynamic Details
    |--------------------------------------------------------------------------
    */

    hideDynamicFields();


    /*
    |--------------------------------------------------------------------------
    | Initial Amenities
    |--------------------------------------------------------------------------
    */

    clearAmenities();

})();


/*
|--------------------------------------------------------------------------
| Expose Functions
|--------------------------------------------------------------------------
|
| The HTML buttons can call these functions directly:
|
|   nextStep()
|   previousStep()
|   showStep()
|
| Keeping them on window makes them available to inline onclick handlers.
|--------------------------------------------------------------------------
*/

window.nextStep =
    nextStep;


window.previousStep =
    previousStep;


window.showStep =
    showStep;


window.goToStep =
    goToStep;


window.buildPropertyReview =
    buildPropertyReview;


window.showDynamicFields =
    showDynamicFields;


window.showAmenities =
    showAmenities;


window.clearAmenities =
    clearAmenities;


window.displayPhotoPreviews =
    displayPhotoPreviews;


window.removePhoto =
    removePhoto;


window.displayMainPhotoPreview =
    displayMainPhotoPreview;


window.removeMainPhoto =
    removeMainPhoto;


/*
|--------------------------------------------------------------------------
| Final Safety Check
|--------------------------------------------------------------------------
*/

if (
    typeof subcategories === "undefined"
) {

    console.error(
        "UrbanNest: subcategories data was not loaded from PHP."
    );

}


if (
    typeof amenities === "undefined"
) {

    console.error(
        "UrbanNest: amenities data was not loaded from PHP."
    );

}


if (
    typeof amenityConfig === "undefined"
) {

    console.error(
        "UrbanNest: amenity configuration was not loaded."
    );

}


/*
|--------------------------------------------------------------------------
| END OF ADD PROPERTY JAVASCRIPT
|--------------------------------------------------------------------------
*/