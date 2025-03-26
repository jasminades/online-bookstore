CREATE TABLE `users` (
  `user_id` int PRIMARY KEY,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `email` varchar(255) UNIQUE,
  `password` varchar(255)
);

CREATE TABLE `books` (
  `book_id` int PRIMARY KEY,
  `title` varchar(255),
  `author` varchar(255),
  `price` decimal,
  `genre` varchar(255),
  `category_id` int
);

CREATE TABLE `orders` (
  `order_id` int PRIMARY KEY,
  `user_id` int,
  `order_date` date,
  `total_amount` decimal
);

CREATE TABLE `categories` (
  `category_id` int PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `reviews` (
  `review_id` int PRIMARY KEY,
  `book_id` int,
  `user_id` int,
  `rating` int,
  `comment` text
);

ALTER TABLE `books` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
