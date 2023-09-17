<?php

/** 
 * @var array<int, float> $wv_risk_margin 
 * Key is the limit, and value is Risk Margin
 */
$wv_risk_margin = [
    25000 => 1.05,
    50000 => 1.08,
    100000 => 1.10 ];

/** 
 * @var array<int, float> $wv_risk_margin 
 * Key is the wv class, and value is the hazard factor
 */

$wv_hazard_factor = [
    0 => 1.00,
    1 => 1.10,
    2 => 1.20,
    3 => 1.40,
    4 => 1.45,
];


/** 
 * @var array<int, float> $wv_limit_factor
 * Key is the wv class, and value is the hazard factor
 */

$wv_limit_factor = [
    25000 => 0.005,
    50000 => 0.010,
    100000 => 0.020,
];


/** 
 * @var array<int, float> $wv_base_premium
 * Key is the total_employees, and value is the base premium 
 */
$wv_base_premium = [
    ["max" => 50, "premium" => 5399],
    ["max" => 70, "premium" => 6919],
    ["max" => 100, "premium" => 8299],
    ["max" => 150, "premium" => 10049],
    ["max" => 200, "premium" => 11499],
    ["max" => 250, "premium" => 12899],
    ["max" => 350, "premium" => 14699],
    ["max" => 500, "premium" => 16949],
    ["max" => 750, "premium" => 19199],
    ["max" => 751, "premium" => 7,]
];

/** 
 * @var array<int, array<string, mixed>> $wv_cass
 * the key is the and the value is an array containing the industry title and the wv_class 
 */
$wv_class = [
    1 => ["industry_title" => "Agricultural Production - Crops", "wv_class" => 1,],
    2 => ["industry_title" => "Agricultural Production - Livestock", "wv_class" => 1,],
    7 => ["industry_title" => "Agricultural Services", "wv_class" => 1,],
    8 => ["industry_title" => "Forestry", "wv_class" => 1,],
    9 => ["industry_title" => "Fishing. Hunting. & Trapping", "wv_class" => 1,],
    10 => ["industry_title" => "Metal. Mining", "wv_class" => 1,],
    12 => ["industry_title" => "Coal Mining", "wv_class" => 1,],
    13 => ["industry_title" => "Oil & Gas Extraction", "wv_class" => 1,],
    14 => ["industry_title" => "Nonmetallic Minerals. Except Fuels", "wv_class" => 1,],
    15 => ["industry_title" => "General Building Contractors", "wv_class" => 1,],
    16 => ["industry_title" => "Heavy Construction. Except Building", "wv_class" => 1,],
    17 => ["industry_title" => "Special Trade Contractors", "wv_class" => 1,],
    20 => ["industry_title" => "Food & Kindred Products", "wv_class" => 1,],
    21 => ["industry_title" => "Tobacco Products", "wv_class" => 1,],
    22 => ["industry_title" => "Textile Mill Products", "wv_class" => 1,],
    23 => ["industry_title" => "Apparel & Other Textile Products", "wv_class" => 1,],
    24 => ["industry_title" => "Lumber & Wood Products", "wv_class" => 1,],
    25 => ["industry_title" => "Furniture & Fixtures", "wv_class" => 1,],
    26 => ["industry_title" => "Paper & Allied Products", "wv_class" => 1,],
    27 => ["industry_title" => "Printing & Publishing", "wv_class" => 1,],
    28 => ["industry_title" => "Chemical & Allied Products", "wv_class" => 1,],
    29 => ["industry_title" => "Petroleum & Coal Products", "wv_class" => 1,],
    30 => ["industry_title" => "Rubber & Miscellaneous Plastics Products", "wv_class" => 1,],
    31 => ["industry_title" => "Leather & Leather Products", "wv_class" => 1,],
    32 => ["industry_title" => "Stone. Clay. & Glass Products", "wv_class" => 1,],
    33 => ["industry_title" => "Primary Metal Industries", "wv_class" => 1,],
    34 => ["industry_title" => "Fabricated Metal Products", "wv_class" => 1,],
    35 => ["industry_title" => "Industrial Machinery & Equipment", "wv_class" => 1,],
    36 => ["industry_title" => "Electronic & Other Electric Equipment", "wv_class" => 1,],
    37 => ["industry_title" => "Transportation Equipment", "wv_class" => 1,],
    38 => ["industry_title" => "Instruments & Related Products", "wv_class" => 1,],
    39 => ["industry_title" => "Miscellaneous Manufacturing Industries", "wv_class" => 1,],
    40 => ["industry_title" => "Railroad Transportation", "wv_class" => 1,],
    41 => ["industry_title" => "Local & Interurban Passenger Transit", "wv_class" => 1,],
    42 => ["industry_title" => "Trucking & Warehousing", "wv_class" => 1,],
    43 => ["industry_title" => "US Postal Service", "wv_class" => 1,],
    44 => ["industry_title" => "Water Transportation", "wv_class" => 1,],
    45 => ["industry_title" => "Transportation by Air", "wv_class" => 1,],
    46 => ["industry_title" => "Pipelines. Except Natural Gas", "wv_class" => 1,],
    47 => ["industry_title" => "Transportation Services", "wv_class" => 1,],
    48 => ["industry_title" => "Communications", "wv_class" => 1,],
    49 => ["industry_title" => "Electric. Gas. & Sanitary Services", "wv_class" => 1,],
    50 => ["industry_title" => "Wholesale Trade - Durable Goods", "wv_class" => 1,],
    51 => ["industry_title" => "Wholesale Trade - Nondurable Goods", "wv_class" => 1,],
    52 => ["industry_title" => "Building Materials & Gardening Supplies", "wv_class" => 2,],
    53 => ["industry_title" => "General Merchandise Stores", "wv_class" => 2,],
    54 => ["industry_title" => "Food Stores", "wv_class" => 2,],
    55 => ["industry_title" => "Automative Dealers & Service Stations", "wv_class" => 2,],
    56 => ["industry_title" => "Apparel & Accessory Stores", "wv_class" => 2,],
    57 => ["industry_title" => "Furniture & Homefurnishings Stores", "wv_class" => 2,],
    58 => ["industry_title" => "Eating & Drinking Places", "wv_class" => 2,],
    59 => ["industry_title" => "Miscellaneous Retail", "wv_class" => 2,],
    60 => ["industry_title" => "Depository Institutions", "wv_class" => 1,],
    61 => ["industry_title" => "Nondepository Institutions", "wv_class" => 1,],
    62 => ["industry_title" => "Security & Commodity Brokers", "wv_class" => 1,],
    63 => ["industry_title" => "Insurance Carriers", "wv_class" => 1,],
    64 => ["industry_title" => "Insurance Agents. Brokers. & Service", "wv_class" => 1,],
    65 => ["industry_title" => "Real Estate", "wv_class" => 1,],
    67 => ["industry_title" => "Holding & Other Investment Offices", "wv_class" => 1,],
    70 => ["industry_title" => "Hotels & Other Lodging Places", "wv_class" => 1,],
    72 => ["industry_title" => "Personal Services", "wv_class" => 1,],
    73 => ["industry_title" => "Business Services", "wv_class" => 1,],
    75 => ["industry_title" => "Auto Repair. Services. & Parking", "wv_class" => 1,],
    76 => ["industry_title" => "Miscellaneous Repair Services", "wv_class" => 1,],
    78 => ["industry_title" => "Motion Pictures", "wv_class" => 1,],
    79 => ["industry_title" => "Amusement & Recreation Services", "wv_class" => 1,],
    80 => ["industry_title" => "Health Services", "wv_class" => 5,],
    81 => ["industry_title" => "Legal Services", "wv_class" => 1,],
    82 => ["industry_title" => "Educational Services", "wv_class" => 4,],
    83 => ["industry_title" => "Social Services", "wv_class" => 3,],
    84 => ["industry_title" => "Museums. Botanical. Zoological Gardens", "wv_class" => 1,],
    86 => ["industry_title" => "Membership Organizations", "wv_class" => 1,],
    87 => ["industry_title" => "Engineering & Management Services", "wv_class" => 1,],
    88 => ["industry_title" => "Private Households", "wv_class" => 1,],
    89 => ["industry_title" => "Services. Not Elsewhere Classified", "wv_class" => 1,],
    91 => ["industry_title" => "Public Executive. Legislative. & General", "wv_class" => 1,],
    92 => ["industry_title" => "Public Justice. Public Order. & Safety", "wv_class" => 1,],
    93 => ["industry_title" => "Public Finance. Taxation. & Monetary Policy", "wv_class" => 1,],
    94 => ["industry_title" => "Public Administration of Human Resources", "wv_class" => 1,],
    95 => ["industry_title" => "Public Environmental Quality & Housing", "wv_class" => 1,],
    96 => ["industry_title" => "Public Administration of Economic Programs", "wv_class" => 1,],
    97 => ["industry_title" => "National Security & International Affairs", "wv_class" => 1,],
    98 => ["industry_title" => "Zoological Gardens", "wv_class" => 1,],
    99 => ["industry_title" => "Non-Classifiable Establishments", "wv_class" => 1,],
];
