-- =====================================================================
--  Malcolm Lismore Photography - Sample / demonstration data
--  ALL DATA IS FICTIONAL. Names, emails, phone numbers, addresses and
--  booking enquiries are invented for demonstration purposes only.
--  Run AFTER schema.sql
-- =====================================================================

USE malcolm_lismore;

-- ---------------------------------------------------------------------
--  Categories (5 gallery categories)
-- ---------------------------------------------------------------------
INSERT INTO categories (category_id, name, slug, description) VALUES
(1, 'Scottish Landscapes', 'landscape',
 'The rugged hills, sea lochs and open moorland of the North West Highlands, from golden first light to brooding storm skies.'),
(2, 'Wildlife', 'wildlife',
 'Native Scottish wildlife photographed in its natural habitat, from red deer on the moor to pine marten in the ancient forest.'),
(3, 'Coastal Birds', 'birds',
 'Seabirds and waders along the dramatic Highland coastline, from puffins on the cliffs to gannets over the open water.'),
(4, 'Weddings', 'weddings',
 'Relaxed, documentary wedding photography that captures the emotion of the day across Scotland''s most beautiful venues.'),
(5, 'Portraits and Special Events', 'portraits-events',
 'Portrait sessions, family groups and special events, styled with natural light and a calm, unhurried approach.');

-- ---------------------------------------------------------------------
--  Photos (gallery records, grouped by category)
-- ---------------------------------------------------------------------
INSERT INTO photos (category_id, title, description, image_path, alt_text, location) VALUES
-- Landscapes (category 1)
(1, 'Sunrise Over the Cuillin', 'First light spills across the jagged Cuillin ridge on the Isle of Skye, turning the rock a burnished copper.', 'assets/images/landscape-1.jpg', 'Sunrise illuminating the jagged Cuillin mountain ridge on the Isle of Skye', 'Isle of Skye'),
(1, 'Loch at Dawn', 'Still water mirrors the pines on a Highland loch before the morning mist lifts.', 'assets/images/landscape-2.jpg', 'Calm Highland loch at dawn with mist drifting between pine trees', 'Loch Maree'),
(1, 'Storm Over Ben Nevis', 'A clearing storm reveals the southern approaches to Ben Nevis under a breaking sky.', 'assets/images/landscape-3.jpg', 'Dramatic storm clouds clearing above the slopes of Ben Nevis', 'Fort William'),
(1, 'Golden Moorland', 'Heather and bracken glow gold in the low evening sun across an open Highland glen.', 'assets/images/landscape-4.jpg', 'Golden heather moorland glowing in low evening sunlight', 'Cairngorms'),

-- Wildlife (category 2)
(2, 'Red Deer Stag', 'A noble red deer stag pauses among the Caledonian pines, alert and watchful.', 'assets/images/wildlife-1.jpg', 'Red deer stag standing among tall pine trees in a forest', 'Rothiemurchus'),
(2, 'Forest Sentinel', 'A young deer stands in dappled light, framed by the trunks of an ancient woodland.', 'assets/images/wildlife-2.jpg', 'Deer standing in dappled forest light between tree trunks', 'Glen Affric'),
(2, 'Quiet Grazing', 'A lone deer grazes peacefully as soft mist drifts through the trees at first light.', 'assets/images/wildlife-3.jpg', 'Deer grazing in a misty forest clearing at dawn', 'Beinn Eighe'),

-- Coastal Birds (category 3)
(3, 'Puffin Colony', 'Atlantic puffins crowd the clifftop burrows against a warm evening sky.', 'assets/images/birds-1.jpg', 'Atlantic puffins gathered on a coastal cliff at sunset', 'Isle of May'),
(3, 'Gannets on the Wing', 'Gannets sweep low over the water as they hunt along the Highland coast.', 'assets/images/birds-2.jpg', 'Seabirds flying low over the sea near a coastal cliff', 'Cape Wrath'),
(3, 'Clifftop Lookout', 'A shag dries its wings on the rocks while more birds wheel overhead.', 'assets/images/birds-3.jpg', 'Coastal bird perched on a cliff with others flying above', 'Handa Island'),

-- Weddings (category 4)
(4, 'The First Look', 'A quiet moment together before the ceremony, framed by soft Highland light.', 'assets/images/wedding-1.jpg', 'Bride and groom sharing a quiet moment before their wedding ceremony', 'Plockton'),
(4, 'Golden Hour Vows', 'Newlyweds walk hand in hand through golden evening light.', 'assets/images/wedding-2.jpg', 'Newly married couple walking hand in hand in golden light', 'Isle of Skye'),
(4, 'Celebration', 'Joy and laughter as the reception gets underway among family and friends.', 'assets/images/wedding-3.jpg', 'Wedding celebration with the couple surrounded by warm light', 'Inverness'),

-- Portraits and Special Events (category 5)
(5, 'Natural Light Portrait', 'A relaxed portrait session using only soft, directional window light.', 'assets/images/portrait-1.jpg', 'Studio portrait lit by soft natural light', 'Ullapool Studio'),
(5, 'Editorial Portrait', 'A bolder, editorial portrait with dramatic contrast and mood.', 'assets/images/portrait-2.jpg', 'Editorial style portrait with dramatic lighting', 'Ullapool Studio'),
(5, 'Family Gathering', 'A special family event captured with a candid, documentary eye.', 'assets/images/event-1.jpg', 'Family group photographed together at a special event', 'Gairloch'),
(5, 'Milestone Celebration', 'A birthday celebration documented from the quiet moments to the party.', 'assets/images/event-2.jpg', 'Guests celebrating at a milestone birthday event', 'Dornoch');

-- ---------------------------------------------------------------------
--  Services (packages for the prices page)
--  starting_price is NULL for "custom pricing" packages.
-- ---------------------------------------------------------------------
INSERT INTO services (name, description, starting_price, price_note, includes, image_path, alt_text, sort_order, is_active) VALUES
('Wedding Photography',
 'Full-day, documentary wedding coverage from morning preparations to the first dance.',
 1000.00, 'Starting from Â£1000',
 'Full-day coverage from preparations to first dance\nA second photographer for the larger celebrations\nOnline private gallery of edited high-resolution images\nPre-wedding consultation and planning session',
 'assets/images/service-wedding.jpg', 'Bride and groom together at their wedding', 1, 1),

('Portrait Sessions',
 'Relaxed individual, couple or family portrait sessions using natural Highland light.',
 200.00, 'Starting from Â£200',
 'One to two hour session on location or in studio\nGuidance on posing and styling\nA selection of fully retouched images\nOption to purchase additional prints',
 'assets/images/service-portrait.jpg', 'Portrait subject lit by soft natural light', 2, 1),

('Family Photography',
 'Natural, candid portraits of your family set against the beautiful Highland landscape.',
 300.00, 'Starting from Â£300',
 'One hour session at a location of your choice\nCandid and posed group photographs\nA gallery of edited images\nDigital download and print release',
 'assets/images/service-family.jpg', 'Family group photographed together outdoors', 3, 1),

('Special Events',
 'Coverage for birthdays, engagements and private celebrations, big or small.',
 NULL, 'Custom pricing',
 'Flexible coverage to suit the size of your event\nDiscreet documentary approach\nA curated gallery of the key moments\nContact us for a personalised quote',
 'assets/images/service-event.jpg', 'Guests enjoying a special evening event', 4, 1),

('Corporate Events',
 'Professional coverage for conferences, launches and corporate functions.',
 NULL, 'Custom pricing',
 'Coverage of speakers, delegates and key moments\nFast turnaround for press and marketing use\nLicensed images for commercial use\nContact us for a personalised quote',
 'assets/images/service-corporate.jpg', 'Speaker presenting at a corporate conference', 5, 1),

('Landscape & Wildlife Commissions',
 'Bespoke fine-art commissions and print sales of Scottish landscapes and wildlife.',
 NULL, 'Commissioned on request',
 'Bespoke commissions of specific locations or subjects\nFine-art prints on archival paper\nLicensing for editorial and commercial use\nContact us to discuss your requirements',
 'assets/images/service-landscape.jpg', 'Fine-art landscape photograph of the Highlands', 6, 1);

-- ---------------------------------------------------------------------
--  Sample enquiries (for demonstrating the admin area)
-- ---------------------------------------------------------------------
INSERT INTO enquiries (name, email, phone, service_type, event_date, location, budget, message, status, created_at) VALUES
('Eilidh Fraser', 'eilidh.fraser@example.com', '07700 900123', 'Wedding Photography', '2025-06-14', 'Plockton, Ross-shire', 'Â£1500', 'We are planning a small outdoor ceremony by the loch and love your documentary style. Are you available in mid June?', 'new', '2025-01-12 09:24:00'),
('Callum MacLeod', 'callum.macleod@example.com', '07700 900456', 'Portrait Sessions', '2025-03-02', 'Ullapool', 'Â£200 - Â£300', 'Looking for a family portrait session during the Easter holidays. Preferably outdoors at golden hour.', 'contacted', '2025-01-10 14:05:00'),
('Fiona Grant', 'fiona.grant@example.com', NULL, 'Special Events', '2025-05-20', 'Inverness', NULL, 'We are hosting a 40th birthday celebration and would like discreet coverage throughout the evening.', 'new', '2025-01-08 18:40:00'),
('Rory Sinclair', 'rory.sinclair@example.com', '07700 900789', 'Landscape & Wildlife Commissions', NULL, 'Isle of Skye', 'Â£500+', 'Interested in a bespoke print of the Cuillin at sunrise. Do you offer large format fine-art prints?', 'booked', '2025-01-05 11:15:00');

-- ---------------------------------------------------------------------
--  No default administrator account is provided.
--  Create your own admin account before first use (see README.md, section 4).
--  The `admins` table exists but is empty after importing this file.
-- ---------------------------------------------------------------------

