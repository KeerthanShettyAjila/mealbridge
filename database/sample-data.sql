-- OPTIONAL: Run only after registering a donor and an orphanage and approving both.
-- Uses your own accounts; does not add default passwords. Run once to avoid duplicates.
USE mealbridge;
SET @donor=(SELECT id FROM users WHERE role='donor' AND status='approved' ORDER BY id LIMIT 1);
INSERT INTO donations(donor_id,title,food_type,category,quantity,city,address,description,allergens,deadline)
SELECT @donor,'Fresh vegetable rice bowls','Vegetarian','Cooked meals',20,'Manipal','Demo pickup point — update before use','DEMO listing: freshly prepared rice and vegetables. Replace with real food preparation and storage details.','Demo: may contain dairy',DATE_ADD(NOW(),INTERVAL 8 HOUR) WHERE @donor IS NOT NULL;
INSERT INTO donations(donor_id,title,food_type,category,quantity,city,address,description,allergens,deadline)
SELECT @donor,'A basket of seasonal goodness','Vegan','Produce',15,'Udupi','Demo pickup point — update before use','DEMO listing: a selection of fresh seasonal vegetables.','Unknown',DATE_ADD(NOW(),INTERVAL 1 DAY) WHERE @donor IS NOT NULL;
INSERT INTO donations(donor_id,title,food_type,category,quantity,city,address,description,allergens,deadline)
SELECT @donor,'Bread for a brighter morning','Vegetarian','Bakery',12,'Manipal','Demo pickup point — update before use','DEMO listing: fresh bread rolls, stored in clean sealed containers.','Wheat, gluten; may contain milk',DATE_ADD(NOW(),INTERVAL 12 HOUR) WHERE @donor IS NOT NULL;
