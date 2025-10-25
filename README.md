# Droplink API

Droplink is a **pure REST API** file-sharing service that allows users to upload files and receive a shareable download link. The user can choose the access level as **private** or **public**.  
Each shared link is valid for **3 days only**. After expiration, both the file and its record are automatically deleted.

---

## 🚀 Features

- User authentication with **JWT**
- Secure file upload with size and type validation (configurable via `.env`)
- Generate secure download links per upload
- Access levels:
    - **Private:** only the file owner can access it
    - **Public:** anyone with the link can download the file
- **Auto-expiration:** files are removed 3 days after upload
- Clean REST structure using Laravel 12+
- Scheduled task to clean expired files daily

---
