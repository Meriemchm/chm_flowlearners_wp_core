# FlowLearners Core

**Version:** 1.6  
**Author:** Meriem Chami  
**License:** GPL-2.0-or-later  
**Requires:** WordPress 6.0+  
**Tested up to:** WordPress 6.5  

---

## Description

FlowLearners Core is a custom WordPress plugin designed for the **FlowLearners e-learning platform**.  
It provides core features for managing **user groups, class access, schedules, and virtual classrooms** using **Zoom**.  

This plugin is built to be **modular, scalable, and maintainable**, ensuring long-term compatibility with WordPress and other free plugins.

---

## Features

- Dynamic management of **groups and classes**  
- Automatic creation of class pages for each group  
- Direct integration with **Zoom** for virtual classrooms  
- Tutors can configure a **Zoom meeting link** per class/group directly from **Edit Group**  
- Toggle Zoom session access (Open/Closed) per class directly from the admin dashboard with nonce protection  
- Enrolled students see a prominent **"Join Class"** button to join the live Zoom meeting  
- Store and manage **class schedule, days, and periods** via WordPress post meta  
- Role-based access: `administrator`, `tutor`, and `student`  
- Fully dynamic: any new group created in the plugin is automatically recognized  

---

## Installation

1. Upload the `flowlearners-core` folder to the `/wp-content/plugins/` directory.  
2. Activate the plugin through the **Plugins** menu in WordPress.  
3. Ensure the **Groups plugin** is installed and active.  
4. Access the dashboard to manage groups and configure class schedules and Zoom links.  

---

## Usage

- **Manage Groups Table:**  
  Use the `[fl_manage_groups]` shortcode to display and manage all groups, schedules, Zoom links, and access toggles.

- **Edit Group Page:**  
  Use the `[fl_edit_group]` shortcode to update class times, days, periods, and the Zoom meeting link for a specific group.

- **Zoom Classroom Integration:**  
  When a tutor sets a class to **Open**, enrolled students visiting their classroom page see the live class badge and the **"Join Class"** button. When the class is **Closed**, students see a clear waiting notice.

---

## Changelog

**1.6**  
- Replaced Jitsi Meet iframe with **Zoom Virtual Classroom** integration  
- Added Zoom meeting link configuration field in `[fl_edit_group]`  
- Added "Join Class" interactive button for students on classroom pages  
- Added nonce security verification on group updates and Zoom access toggles  
- Updated group management dashboard with Zoom links and status toggles  

**1.5**  
- Added dynamic group detection for class pages  
- Integrated virtual classrooms with toggle access  
- Added class period meta field  
- Improved compatibility with WordPress 6.x  
- Refactored group/page logic for better maintainability  

**1.4**  
- Initial stable release with core group management  
- Class schedule and days management  

---

## License

This plugin is licensed under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html).

---

## Author

Meriem Chami  
[GitHub](https://github.com/Meriemchm) | [Fiverr](https://fr.fiverr.com/meriem_chami)
