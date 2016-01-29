# MeTube 
A web application similar to youtube which allows users to upload video files for other users to watch and subscribe to their channels of content, but also allows uploading and viewing audio files and images. This was the final project in my database systems class I took at Clemson University; I was the only contributor to the code and assets for this project.
## Local Setup (assumes you have your MySql db setup and configured properly with the imported schema for this database)
- install and configure a LAMP server on the machine 
- place all files and subdirectories of the project in the folder which your web server is looking at for web content (if different than /var/www/, some code will need to be updated to reflect the proper location)
- restart your web server to pick up the new files and ensure your PHP and MySQL installations are configured properly
- verify the MeTube app loads and renders appropriately at your server's configured context root for the web application
```c
http:<hostname>:<port>/path/to/webapp/index.php
```
