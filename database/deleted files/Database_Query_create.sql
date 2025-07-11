--update database
--D, perform all these changes following this sequence
ALTER TABLE repairs
MODIFY COLUMN booked_date DATETIME;

ALTER TABLE repairs ADD COLUMN status ENUM('Pending', 'Accepted', 'Declined') DEFAULT 'Pending';

--Ronewa start from here
ALTER TABLE repairs MODIFY COLUMN status ENUM('Pending', 'Accepted', 'Declined', 'In Review', 'Complete', 'Declined after Review') DEFAULT 'Pending';

ALTER TABLE sell ADD COLUMN status ENUM('Pending', 'Accepted', 'Declined', 'In Review', 'Complete', 'Declined after Review') DEFAULT 'Pending';

ALTER TABLE bookings
MODIFY COLUMN booking_time ENUM('8:00-10:00', '10:00-12:00', '12:00-14:00', '14:00-16:00') NOT NULL;

ALTER TABLE bookings
MODIFY COLUMN duration ENUM('20min', '1hr30min', '2hr') NOT NULL;

ALTER TABLE bookings
ADD COLUMN status ENUM('pending', 'accepted', 'declined') DEFAULT 'pending';

ALTER TABLE customers MODIFY phone VARCHAR(10);
ALTER TABLE admins MODIFY phone VARCHAR(10);

--Latest queries updates
ALTER TABLE products ADD quantity int;

-- SQL query that consolidates product records based on the pName column, updates the quantity column by summing up duplicate products, and then deletes the extra records
UPDATE products p1
JOIN (
    SELECT pName, SUM(quantity) AS total_quantity
    FROM products
    GROUP BY pName
) p2
ON p1.pName = p2.pName
SET p1.quantity = p2.total_quantity;
DELETE p1 -- Step 2: Delete the duplicate records, keeping only the first occurrence of each product
FROM products p1
JOIN products p2
ON p1.pName = p2.pName
AND p1.product_id > p2.product_id;

--
ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) DEFAULT 0;


--Database name Computer Complex
--I'm using a local database instead of mysql sever; it's called phpMyAdmin
 --------------------------------------- Execute 1st
CREATE TABLE CUSTOMERS (
    customer_id INT NOT NULL AUTO_INCREMENT ,
    firstName VARCHAR(20) NOT NULL,
    lastName VARCHAR(20) NOT NULL,
    email VARCHAR(20) NOT NULL,
    phone VARCHAR(10) NOT NULL,
    ddress VARCHAR(50) NOT NULL,
    pwrd VARCHAR(20) NOT NULL,
    PRIMARY KEY (customer_id)
);

CREATE TABLE ADMINS (
    admin_id INT NOT NULL AUTO_INCREMENT ,
    firstName VARCHAR(20) NOT NULL,
    lastName VARCHAR(20) NOT NULL,
    email VARCHAR(20) NOT NULL,
    phone VARCHAR(10) NOT NULL,
    ddress VARCHAR(50) NOT NULL,
    dep varchar(20) NOT NULL,
    pwrd VARCHAR(20) NOT NULL,
    PRIMARY KEY (admin_id)
);

CREATE TABLE BOOKINGS(
    booking_id INT NOT NULL AUTO_INCREMENT,
    booking_type VARCHAR(20) NOT NULL,
    booked_on DATE NOT NULL,
    booked_at TIME NOT NULL,
    session_status BOOLEAN NOT NULL,
    PRIMARY KEY (booking_id)
);

CREATE TABLE PRODUCTS (
    product_id INT NOT NULL AUTO_INCREMENT,
    pName VARCHAR(20) NOT NULL,
    discription TEXT NOT NULL,
    price DOUBLE NOT NULL,
    category VARCHAR(20) NOT NULL,
    PRIMARY KEY (product_id)
);

  --------------------------------------- Execute 2nd
CREATE TABLE CARTS(
    cart_id INT NOT NULL AUTO_INCREMENT,
    cart_created DATE NOT NULL,
    quantity INT NOT NULL,
    product_id INT,
    FOREIGN KEY (product_id) REFERENCES PRODUCTS(product_id) ON DELETE SET NULL,
    PRIMARY KEY (cart_id)
);

CREATE TABLE repairs(
    repair_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMERS(customer_id) ON DELETE SET NULL,
    image LONGBLOB NOT NULL,
    description VARCHAR(255) NOT NULL,
    booked_date DATE NOT NULL,
    PRIMARY KEY (repair_id)
);

CREATE TABLE sell(
    sell_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMERS(customer_id) ON DELETE SET NULL,
    image1 LONGBLOB NOT NULL,
    image2 LONGBLOB NOT NULL,
    image3 LONGBLOB NOT NULL,
    description VARCHAR(255) NOT NULL,
    price DOUBLE NOT NULL,
    PRIMARY KEY (repair_id)
);

CREATE TABLE bookings(
    booking_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMERS(customer_id) ON DELETE SET NULL,
    duration INT NOT NULL,
    booking_time TIME NOT NULL,
    booked_date DATE NOT NULL,
    PRIMARY KEY (booking_id)
);


  --------------------------------------- Execute 3rd
CREATE TABLE ORDERS (
    order_id INT NOT NULL AUTO_INCREMENT,
    custumer_name varchar(20) NOT NULL,
    order_Date DATE NOT NULL,
    totalPrice DOUBLE NOT NULL,
    customer_id INT,
    cart_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMERS(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (cart_id) REFERENCES CARTS(cart_id) ON DELETE SET NULL,
    PRIMARY KEY (order_id)
);

  --------------------------------------- Execute 4th
CREATE TABLE PAYMENTS (
    payment_id INT NOT NULL AUTO_INCREMENT,
    paymet_type varchar(20) NOT NULL,
    order_id INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id),
    PRIMARY KEY (payment_id)
);

CREATE TABLE USERS (
    user_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT,
    admin_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMERS(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES ADMINS(admin_id) ON DELETE SET NULL,
    PRIMARY KEY (user_id)
);

  --------------------------------------- Execute 5th
CREATE TABLE REPORTS (
    report_id INT NOT NULL AUTO_INCREMENT,
    booking_id INT,
    order_id INT,
    product_id INT,
    user_id INT,
    FOREIGN KEY (booking_id) REFERENCES BOOKINGS(booking_id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES PRODUCTS(product_id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL,
    PRIMARY KEY (report_id)
);