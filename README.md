# Employee Transfer Panel

Welcome to the Employee Transfer Panel project. This application aims to streamline the process of transferring employees between different headquarters and roles. The panel allows users to search, select, and transfer employees efficiently using an intuitive interface.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Contribution Guidelines](#contribution-guidelines)
- [License](#license)
- [Contact](#contact)

## Overview

The Employee Transfer Panel is a web-based application designed to assist administrators in managing employee transfers. The application offers a user-friendly interface to:
- Select employees from a source list.
- Transfer selected employees to a target list.
- Search and filter employees based on different criteria.

This project is a solution to the issue of efficiently managing employee transfers, ensuring data accuracy and operational efficiency.

## Features

- **Dynamic Employee Selection:** Easily search and filter employees by headquarters, role, and crop type.
- **Batch Transfer:** Transfer multiple employees at once from one list to another.
- **Responsive Design:** The application is fully responsive and works well on various devices.
- **Optimized Performance:** Fast and efficient data fetching and rendering.

## Technologies Used

- **Frontend:**
  - HTML5
  - CSS3
  - JavaScript (Vanilla)
  
- **Backend:**
  - PHP

- **Database:**
  - MySQL

## Installation

Follow these steps to set up the project locally:

1. **Clone the Repository:**
   ```sh
   git clone https://github.com/Sp-177/admin_transfer_panel.git
   cd admin_transfer_panel
   ```

2. **Setup the Database:**
   - Create a MySQL database.
   - Import the provided SQL file to set up the required tables and data.

3. **Configure the Database Connection:**
   - Update the `config.php` file with your database credentials.
     ```php
     $servername = "your_server_name";
     $username = "your_username";
     $password = "your_password";
     $dbname = "unnati_db";
     ```

4. **Run the Application:**
   - Start your local server (e.g., XAMPP, WAMP, LAMP).
   - Navigate to the project directory in your web browser.

## Usage

1. **Select Crop Type:** Choose the crop type to filter relevant headquarters.
2. **Select Headquarters and Role:** Filter employees by selecting the headquarters and role.
3. **Search Employees:** Use the search bar to find specific employees.
4. **Transfer Employees:** Use the buttons to transfer selected employees between the lists.
5. **Submit Changes:** Once all transfers are made, submit the changes.

## Contribution Guidelines

We welcome contributions to enhance this project. To contribute, follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature-name`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/your-feature-name`).
5. Create a new Pull Request.

Please ensure your code adheres to the existing coding standards and includes appropriate documentation.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or inquiries, please contact us at:

- **Email:** shub404.x@gmail.com
- **GitHub Issues:** [Issues Page](https://github.com/Sp-177/admin_transfer_panel/issues)

Thank you for using the Employee Transfer Panel! Your feedback and contributions are greatly appreciated.
