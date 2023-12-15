# shellcheck disable=SC1113
# /bin/bash
echo "git pull"
git pull
echo "build"
docker-compose build
echo "restart"
docker-compose up -d


# shellcheck disable=SC2046
docker rmi $(docker images -f "dangling=true" -q)
