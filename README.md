# PHP Synoptic Project

## Overview

This project is essentially supposed to be a website for collectors using php, html, css and javascript. It is a synoptic for one of my classes.

## Features

- **User Management**: Register, login, and logout functionalities.
- **Friends Management**: Add, remove, and update friend statuses.
- **Group Chats**: Create and participate in group chats.
- **Notifications**: Receive and clear notifications.
- **Profile Management**: Update user profiles and interests.
- **Messaging**: Send and receive messages.

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL
- Web server (e.g., Apache, Nginx)

## Project Structure

- **css/**: Contains stylesheets.
  - `style.css`: Main stylesheet for the application.
- **handlers/**: Contains PHP scripts for handling various actions.
  - `clear_notifications_handler.php`, `delete_chat_handler.php`, etc.
- **includes/**: Contains reusable PHP components.
  - `footer.php`, `header.php`
- **scripts/**: Contains JavaScript files.
  - `message.js`
- **PHP-Synoptic/**: Main application files.
  - `index.php`, `profile.php`, `main_feed.php`, etc.
- **.git/**: Git configuration files.
- **Task 1.pdf**, **Task 3.pdf**: Project task descriptions.

## Code Overview

The PHP Synoptic Project is organized to separate concerns and make it easier for contributors to navigate and understand the codebase. Below is a brief overview of the key components:

### Directory Structure

- **css/**: Contains the stylesheets for the project.
  - `style.css`: Main stylesheet that provides the overall look and feel of the application.

- **handlers/**: Includes PHP scripts that handle various actions such as:
  - `login_handler.php`: Processes user login requests.
  - `register_handler.php`: Manages user registration.
  - `send_message_handler.php`: Handles sending messages between users.
  - Other handlers manage notifications, friend requests, post interactions, and more.

- **includes/**: Contains common PHP components that are reused across the application.
  - `header.php`: Common header included on multiple pages.
  - `footer.php`: Common footer included on multiple pages.

- **scripts/**: Contains JavaScript files that add interactivity to the application.
  - `message.js`: Manages real-time messaging features.

- **PHP-Synoptic/**: The main application directory containing PHP files for different pages.
  - `index.php`: The homepage of the application.
  - `profile.php`: User profile page where users can view and edit their information.
  - `main_feed.php`: Displays the main content feed for users.
  - `friends.php`, `groups.php`, `group_chat.php`, etc.: Handle respective functionalities for friends, groups, and group chats.



## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Contact

For any questions or issues, please contact [jean.cacciattolo.d56977@mcast.edu.mt](mailto:your-email@example.com).
