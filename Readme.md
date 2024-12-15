# How to run 

+ Install docker compose.

+ Set the ip of the download server in build-containers.sh.

+ Run build-containers.sh from the project directory to build the Docker containers and register your video server(s).

+ Note in the kube direcotry, there is also a job to register a video server.

+ kompose is a program which can be used to translate Docker compose configs to Kubernetes configs.

+ To shut down all Docker containers, use the command "docker-compose down"
