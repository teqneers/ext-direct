#!/usr/bin/env bash

SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "${SCRIPT_PATH}" || exit 2

export DOCKER_BUILDKIT=1

VERSIONS=("8.1")

for version in "${VERSIONS[@]}"; do
    docker build \
        -f ./Dockerfile \
        --build-arg "PHP_IMAGE=php:${version}-cli" \
        --tag "teqneers/runtime/php:${version}" \
        .
done
