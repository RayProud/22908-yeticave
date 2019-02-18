-- Add existing categories
INSERT INTO category (title) VALUES('Доски и лыжи'),('Крепления'),('Ботинки'),('Одежда'),('Инструменты'),('Разное');

-- Add some users
INSERT INTO user (email,name,password,image_url,contacts)
VALUES('qwer@email.com','Sam Methews','sammethews11','https://vignette.wikia.nocookie.net/althistory/images/7/74/Recent-portraits-random-people-in-random-places_11.jpg','I dont have any contacts yet'),
('test@email.com','Tom Jones','myfavoritesinger','','+19131233243'),
('thebest@email.com','Mark Aurelius','iamthebest999','http://i.imgur.com/L2bakUz.jpg','LA, first street 17'),
('honey@email.com','Mary Tweets','djwe7f32@#fnd','https://i.dailymail.co.uk/i/pix/2014/03/26/article-0-1C91BEE700000578-336_306x393.jpg','Berlin');

-- Add existing lots
INSERT INTO lot (title,description,image_url,start_price,end_at,bet_step,author_id,category_id)
VALUES('2014 Rossignol District Snowboard','Cool stuff!','img/lot-1.jpg',10999.2,'2019-02-22 00:00:00',10,1,1),
('DC Ply Mens 2016/2017 Snowboard','Outstanding board!11','img/lot-2.jpg',159999.99,'2019-02-23 00:00:00',20,1,1),
('Крепления Union Contact Pro 2015 года размер L/XL','Хорошие крепления','img/lot-3.jpg',8000.0,'2019-02-24 00:00:00',20,2,2),
('Ботинки для сноуборда DC Mutiny Charocal','Ботинки для тех, кому не очень без ботинок','img/lot-4.jpg',10999.0,'2019-02-25 00:00:00',45,3,3),
('Куртка для сноуборда DC Mutiny Charocal','Куртка для любителей курток','img/lot-5.jpg',7500.0,'2019-02-19 00:00:00',45,3,4),
('Маска Oakley Canopy','Маска просто огонь, честно','img/lot-6.jpg',10999.0,'2019-02-25 00:00:00',45,4,6);

-- Add some bets
INSERT INTO bet (amount,author_id,lot_id)
VALUES(11000,2,1),
(11010,3,1),
(16020,3,2),
(17000,4,2),
(8020,1,3),
(7600,2,4);

-- Get all categories
SELECT * FROM category;

-- Get newest lots
SELECT l.created_at, l.description, l.title, l.start_price, l.image_url, c.title AS category_title
FROM lot l
       JOIN category c
            ON l.category_id=c.id
WHERE l.end_at >= NOW()
ORDER BY l.created_at DESC;

-- Get a particular lot
SELECT l.*, c.title AS category_title
FROM lot l
       JOIN category c
            ON l.category_id=c.id
WHERE l.id=1;

-- Update a lot using its id
UPDATE lot l SET title='2000 DC кроссовки' WHERE l.id = 4;

-- Get newest bets of lot#1
SELECT * FROM bet b WHERE b.lot_id=1 ORDER BY created_at DESC;
