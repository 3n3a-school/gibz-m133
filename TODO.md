## Backend

- [x] Create project structure
- [x] Create Simple PHP Templating
- [ ] create PHP Server-Side Router
- [ ] integrate Authentication into Router via PHP-Sessions
	* variable is_authenticated in session
- [ ] create login.html page
	* which is served when not authenticated
- [ ] create database controller
	* globally accessible across all controllers
	* automatic init of database -> sql table creation (install.php?)
- [ ] create api endpoints (w controllers)
	* /admin
		* /sysinfo
		* /users
			* /create
			* /delete
			* /disable
			* /update
		* delete event
	* /ranking
	* /category
	* /event
	* /me
	* /organizer
		* /import
		* create event
		* update event

## Frontend

- [ ] create in react
- [ ] one for admins
- [ ] one for users/organizers
- [ ] use react-query to get info from backend
- [ ] pack using webpack
- [ ] serve via router in php
