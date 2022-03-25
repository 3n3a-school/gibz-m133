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
- [x] create importer
- [ ] create rank hiding for user
- [ ] make everything work in xampp
- [ ] Homepage show latest events

## SQL Queries

**get event metadata** ✅

```sql
SELECT name, description
FROM event_meta
WHERE event_id = ?
```

**get event (with organizer club)** ✅

```sql
SELECT event.id, event.name, event.date, event.place, club.name AS club_name
FROM event
LEFT JOIN club
ON event.organizer_id = club.id;
```

**get userinfo with club** ✅

```sql
SELECT first_name, last_name, birthdate, club.name AS club_name, email, is_active, is_verified 
FROM users 
LEFT JOIN club
ON club_id = club.id
WHERE username = ?
```

**trigger on ranking -> find users** ✅

```sql
INSERT INTO user_ranking (user_id, ranking_id)
	SELECT users.id, ranking.id FROM users
		INNER JOIN ranking 
		ON ranking.participant_name = (
			SELECT CONCAT(users.first_name, ' ', users.last_name) AS full_name
		) AND
		ranking.birthyear = DATE_FORMAT(users.birthdate, '%y') AND
		ranking.club = (SELECT name FROM club WHERE id = users.club_id)
		WHERE users.id NOT IN (
			SELECT user_id FROM user_ranking
		)
```

**get events user participated in** ✅

```sql
SELECT users.id AS user_id, users.username, event.id AS event_id, event.name, category.name AS category_name,
ranking.position, ranking.time
FROM user_ranking
JOIN ranking ON user_ranking.ranking_id = ranking.id
JOIN users ON user_ranking.user_id = users.id
JOIN event ON ranking.event_id = event.id
JOIN category ON ranking.category_id = category.id
WHERE users.id = ?
```

**get mneta for category in event** ✅

```sql
SELECT * FROM event_category_meta
JOIN category ON category.id = category_id
WHERE event_id = ?
AND category.name = ?
```

**get list of categories** ✅

```sql
SELECT id, name FROM category
```

**get ranking of category in event** ✅

```sql
SELECT * FROM ranking
JOIN category ON category_id = category.id
WHERE category.name = ?
AND event_id = ?
ORDER BY -position DESC
```
