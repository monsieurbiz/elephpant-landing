start:
	cd app && symfony local:server:start
	docker run --rm -d -ti -p 3306:3306 -e MYSQL_ALLOW_EMPTY_PASSWORD=1 -e MYSQL_DATABASE=app mysql:latest
