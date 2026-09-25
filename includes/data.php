<?php

// Available states
$UN_STATES = [
    "Gujarat",
    "Maharashtra"
];


// Available cities
$UN_CITIES = [
    "Ahmedabad",
    "Surat",
    "Vadodara",
    "Rajkot",
    "Gandhinagar",
    "Bhavnagar",
    "Mumbai"
];


// Property categories
$UN_CATEGORIES = [
    "Residential",
    "Commercial",
    "Land"
];


// Property amenities
$UN_AMENITIES = [
    "Parking",
    "Swimming Pool",
    "Garden",
    "Security",
    "Lift"
];


// Property details
$UN_PROPERTIES = [

    // Property 1
    [
        "id" => "UN001",
        "title" => "Luxury Apartment",
        "city" => "Ahmedabad",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "Apartment",
        "price" => 6500000,
        "purpose" => "buy",
        "status" => "For Sale",
        "bedrooms" => 3,
        "bathrooms" => 3,
        "area" => 1850,
        "image" => "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9"
    ],

    // Property 2
    [
        "id" => "UN002",
        "title" => "Modern Family House",
        "city" => "Surat",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "House",
        "price" => 8500000,
        "purpose" => "buy",
        "status" => "For Sale",
        "bedrooms" => 4,
        "bathrooms" => 3,
        "area" => 2200,
        "image" => "https://images.unsplash.com/photo-1600585154340-be6161a56a0c"
    ],

    // Property 3
    [
        "id" => "UN003",
        "title" => "Beautiful Villa",
        "city" => "Vadodara",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "Villa",
        "price" => 12000000,
        "purpose" => "buy",
        "status" => "For Sale",
        "bedrooms" => 4,
        "bathrooms" => 4,
        "area" => 3000,
        "image" => "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c"
    ],

    // Property 4
    [
        "id" => "UN004",
        "title" => "Premium Office Space",
        "city" => "Ahmedabad",
        "state" => "Gujarat",
        "category" => "Commercial",
        "type" => "Office",
        "price" => 9500000,
        "purpose" => "buy",
        "status" => "For Sale",
        "bedrooms" => 0,
        "bathrooms" => 2,
        "area" => 2500,
        "image" => "https://images.unsplash.com/photo-1497366754035-f200968a6e72"
    ],

    // Property 5
    [
        "id" => "UN005",
        "title" => "Residential Plot",
        "city" => "Rajkot",
        "state" => "Gujarat",
        "category" => "Land",
        "type" => "Plot",
        "price" => 3500000,
        "purpose" => "buy",
        "status" => "For Sale",
        "bedrooms" => 0,
        "bathrooms" => 0,
        "area" => 1800,
        "image" => "https://images.unsplash.com/photo-1500382017468-9049fed747ef"
    ],

    // Property 6
    [
        "id" => "UN006",
        "title" => "Furnished Apartment",
        "city" => "Ahmedabad",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "Apartment",
        "price" => 25000,
        "purpose" => "rent",
        "status" => "For Rent",
        "bedrooms" => 2,
        "bathrooms" => 2,
        "area" => 1400,
        "image" => "https://images.unsplash.com/photo-1600210492486-724fe5c67fb0"
    ],

    // Property 7
    [
        "id" => "UN007",
        "title" => "Family House for Rent",
        "city" => "Surat",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "House",
        "price" => 30000,
        "purpose" => "rent",
        "status" => "For Rent",
        "bedrooms" => 3,
        "bathrooms" => 2,
        "area" => 1800,
        "image" => "https://images.unsplash.com/photo-1600607687920-4e2a09cf159d"
    ],

    // Property 8
    [
        "id" => "UN008",
        "title" => "Luxury Rental Villa",
        "city" => "Vadodara",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "Villa",
        "price" => 45000,
        "purpose" => "rent",
        "status" => "For Rent",
        "bedrooms" => 4,
        "bathrooms" => 3,
        "area" => 2800,
        "image" => "https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea"
    ],

    // Property 9
    [
        "id" => "UN009",
        "title" => "Modern Apartment",
        "city" => "Gandhinagar",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "Apartment",
        "price" => 5500000,
        "purpose" => "sell",
        "status" => "For Sale",
        "bedrooms" => 3,
        "bathrooms" => 2,
        "area" => 1600,
        "image" => "https://images.unsplash.com/photo-1600607687644-c7171b42498f"
    ],

    // Property 10
    [
        "id" => "UN010",
        "title" => "Commercial Shop",
        "city" => "Bhavnagar",
        "state" => "Gujarat",
        "category" => "Commercial",
        "type" => "Shop",
        "price" => 4000000,
        "purpose" => "sell",
        "status" => "For Sale",
        "bedrooms" => 0,
        "bathrooms" => 1,
        "area" => 900,
        "image" => "https://images.unsplash.com/photo-1497366811353-6870744d04b2"
    ],

    // Property 11
    [
        "id" => "UN011",
        "title" => "Spacious Family Home",
        "city" => "Rajkot",
        "state" => "Gujarat",
        "category" => "Residential",
        "type" => "House",
        "price" => 7200000,
        "purpose" => "sell",
        "status" => "For Sale",
        "bedrooms" => 3,
        "bathrooms" => 3,
        "area" => 2100,
        "image" => "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde"
    ],

    // Property 12
    [
        "id" => "UN012",
        "title" => "Luxury City Apartment",
        "city" => "Mumbai",
        "state" => "Maharashtra",
        "category" => "Residential",
        "type" => "Apartment",
        "price" => 15000000,
        "purpose" => "sell",
        "status" => "For Sale",
        "bedrooms" => 3,
        "bathrooms" => 3,
        "area" => 2000,
        "image" => "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c"
    ]

];
