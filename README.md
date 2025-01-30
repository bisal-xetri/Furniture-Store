# Furniture E-Commerce Website

## Overview
This is a dynamic e-commerce website for buying furniture online. It allows users to browse furniture, place orders, and make payments using eSewa. The platform is built using PHP and MySQL for backend operations, with a frontend powered by HTML, CSS, and JavaScript.

## Features
- User authentication (login and signup required for purchasing)
- Browse available furniture
- Add furniture to cart and place orders
- Payment integration with eSewa
- Email order confirmation using PHP `mail()` function
- Order history management for users

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP, MySQL
- **Database**: MySQL
- **Payment Gateway**: eSewa
- **Email Notifications**: PHP `mail()` function

## Installation & Setup
### Prerequisites
- XAMPP or any local server with PHP and MySQL
- Composer (if using PHPMailer for email notifications)

### Steps to Install
1. Clone the repository:
   ```sh
   git clone https://github.com/yourusername/furniture-ecommerce.git
   ```
2. Move the project folder to your server directory (e.g., `htdocs` for XAMPP).
3. Import the database:
   - Open `phpMyAdmin`
   - Create a new database (e.g., `furniture_db`)
   - Import `furniture_db.sql` file from the project
4. Configure database connection in `config/constant.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'furniture_db');
   ```
5. Start the server and access the project in the browser:
   ```
   http://localhost/furniture-ecommerce/
   ```

## Usage
1. **Register/Login** as a user
2. **Browse furniture** available for sale
3. **Select furniture** and enter delivery details
4. **Confirm order** and proceed with payment
5. **Receive order confirmation** via email
6. **Check order history** in the user dashboard

## Payment Integration (eSewa)
- The project supports eSewa for payments.
- Ensure valid eSewa Merchant credentials are configured in the code.
- Payment button appears only for valid order amounts.

## Issues & Debugging
- If the **Pay with eSewa** button is not appearing, check:
  - Transaction amount is valid (`ES705 - Invalid transaction amount` error)
  - Proper eSewa Merchant credentials are configured
- If email notifications are not sent, verify mail server settings and SMTP configurations.

## Contribution
Feel free to contribute by opening issues or submitting pull requests.

## License
This project is open-source and available under the MIT License.

