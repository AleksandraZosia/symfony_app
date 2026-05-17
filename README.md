To start the application use:

docker compose up -d --build

You may see an error reffering to container networking - connectivity on endpoint - bind for failed: port is already allocated.

It means that you already have a server using this endpoint => go to docker-compose.yaml file.

Find the 'container_name' mentioned in the error and change the ports setting.

Example:

db:
container_name: symfony_db
ports:
-"5432:5432"

to

db:
container_name: symfony_db
ports:
-"5433:5432"

After implementing the change run:
docker compose down

And rebuild with:
docker compose up -d --build

When the app is running go to:

localhost:8080
