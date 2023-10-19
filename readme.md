# Scheduled Menus WordPress Plugin

A WordPress plugin that allows users to set specific dates for menus to be active.

## Description

With the Scheduled Menus plugin, you can schedule specific WordPress menus to appear on your website based on the date range you set. This is particularly useful for promotional periods, events, or any other situation where you would want a different menu to show up for a specific period.

## Features

- Easily set start and end dates for each menu.
- Multiple scheduled menus can be added.
- Overlapping schedules are handled intelligently to ensure there's no conflict.
- User-friendly admin interface integrated into the WordPress settings.

## Installation

1. Download the PHP file for the plugin.
2. Upload the plugin to your `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to 'Settings' -> 'Scheduled Menus' to configure your scheduled menus.

## Usage

1. **Adding a New Scheduled Menu**
   - Click on the 'Add New' button.
   - Select the desired 'Menu Location' and the 'Menu' you want to schedule.
   - Set the 'Start Date' and 'End Date' for the menu to be active.
   - Click 'Save Changes'.

2. **Deleting a Scheduled Menu**
   - Click on the 'Delete' link next to the scheduled menu you want to remove.
   - Confirm the deletion.
   - Click 'Save Changes'.

3. **Note**: The plugin checks the current date and compares it with the start and end dates of the scheduled menus. If the current date falls within the range of a scheduled menu, that menu will be displayed. If there are overlapping schedules for the same menu location, the plugin will intelligently handle the conflict and display the correct menu.

## Author

- **Ben Ferdinands**

## Version

- 1.0

## Support

For any issues or feature requests, please contact the author or raise an issue on the GitHub repository.

## License

This project is open-source. Please ensure to mention the author if you are using or distributing this plugin.
