## Backend

- [x] Create project structure
- [x] Create Simple PHP Templating
- [x] create PHP Server-Side Router
- [x] integrate Authentication into Router via PHP-Sessions
	* variable is_authenticated in session
- [x] create login.html page
	* which is served when not authenticated
- [x] create database controller
	* globally accessible across all controllers
	* automatic init of database -> sql table creation (install.php?)
- [x] fix installer
- [x] add additional prefill to installer
		
	
## Frontend

- [x] one for users/organizers
- [x] add ranking tables
- [x] add events table
- [x] add club select to registration
- [x] add event meta in event calender
- [ ] create importer


## SQL Queries

**get event metadata**

```sql
SELECT name, description
FROM event_meta
WHERE event_id = ?
```

**get event (with organizer club)**

```sql
SELECT event.id, event.name, event.date, event.place, club.name AS club_name
FROM event
LEFT JOIN club
ON event.organizer_id = club.id;
```

**get userinfo with club**

```sql
SELECT first_name, last_name, birthdate, club.name AS club_name, email, is_active, is_verified 
FROM users 
LEFT JOIN club
ON club_id = club.id
WHERE username = ?
```

**get users' rankings**

```sql
SELECT [only select what you need...] FROM user_ranking
JOIN ranking ON user_ranking.ranking_id = ranking.id
JOIN users ON user_ranking.user_id = users.id
WHERE hidden = FALSE
AND users.username = ?
```

**get events user participated in**

```sql
SELECT users.username, event.name FROM user_ranking
JOIN ranking ON user_ranking.ranking_id = ranking.id
JOIN users ON user_ranking.user_id = users.id
JOIN event ON ranking.event_id = event.id
WHERE hidden = FALSE
AND users.username = ?
GROUP BY event.name
```

**get mneta for category in event**

```sql
SELECT * FROM event_category_meta
JOIN category ON category.id = category_id
WHERE event_id = ?
AND category.name = ?
```

**get list of categories**

```sql
SELECT id, name FROM category
```

**get ranking of category in event**

```sql
SELECT * FROM ranking
JOIN category ON category_id = category.id
WHERE category.name = ?
AND event_id = ?
ORDER BY position ASC
```