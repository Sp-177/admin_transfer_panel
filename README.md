# Admin Transfer Panel

Welcome to the Admin Transfer Panel project! This PHP-based administration panel is designed to streamline user transfers and logging processes within an agricultural organization. It leverages MySQL for efficient data management and provides a user-friendly interface for administrators.

![Admin Transfer Panel](assets/admin_transfer_panel.png)

## Features

- **Dynamic Headquarters Management**: Fetch headquarters based on crop types dynamically using AJAX calls.
- **Role-Based User Filtering**: Filter users based on headquarters and role, ensuring precise user management.
- **Transaction Logging**: Automatically log user transfers to maintain an audit trail of administrative actions.
- **Intuitive User Interface**: Simple and responsive design for seamless user experience.

## Installation

To get started with Admin Transfer Panel locally, follow these steps:

1. **Clone the repository**:

   ```bash
   git clone https://github.com/Sp-177/admin_transfer_panel.git
   cd admin_transfer_panel
   ```

2. **Set up the database**:

   - Import `database/unnati_db.sql` into your MySQL server.
   - Update `config.php` with your MySQL database credentials.

3. **Launch the application**:

   - Start your local server (e.g., Apache).
   - Navigate to `http://localhost/admin_transfer_panel` in your web browser.

## Usage

- **Headquarters Management**: Use dropdowns to select crop types and view available headquarters.
- **User Transfer**: Move users between headquarters using intuitive interface controls.
- **Logging**: Track every transfer operation with automatic logging to ensure accountability.

## Project Contributions

This project reflects my passion for enhancing administrative processes through technology. Here's what I've contributed:

- **Frontend Development**: Designed and implemented the user interface using HTML, CSS, and JavaScript.
- **Backend Logic**: Developed PHP scripts for database interactions, including fetching data and updating records.
- **Database Design**: Designed and optimized the MySQL database schema (`unnati_db.sql`) to efficiently store and retrieve data.

## Future Enhancements

- **User Authentication**: Implement user authentication and access control for enhanced security.
- **Real-Time Updates**: Introduce WebSocket integration for real-time updates on user transfers.
- **Enhanced Logging**: Expand logging capabilities with additional metadata and reporting features.

## Contributing

Contributions are welcome! Here's how you can contribute:

- Fork the repository and create a new branch.
- Make your improvements or fixes.
- Submit a pull request with a detailed description of your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Get Involved

Feel free to reach out if you have any questions, ideas for improvement, or if you'd like to collaborate on enhancing this project further. Your feedback and contributions are highly valued!
