#!/bin/bash -l

set -o errexit -o nounset -o xtrace

function deploy_artifact() {
  tar xvzf application.tgz
  rm -f application.tgz
}
export -f deploy_artifact

function install_dependencies() {
  mkdir -p ~/.local/bin
}
export -f install_dependencies

function copy_dist_files() {
  return 0
}
export -f copy_dist_files

function protect_application() {
  return 0
}
export -f protect_application

function build_app() {
  cd ${APP_HOME}/app
  php -d memory_limit=-1 ./bin/console asset-map:compile -n -v
  php -d memory_limit=-1 ./bin/console doctrine:migr:migr -n -v
}
export -f build_app

function run_app() {
  cd ${APP_HOME}/app
  php -d memory_limit=-1 ./bin/console cache:warmup -v
}
export -f run_app
