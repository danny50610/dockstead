#!/usr/bin/env bash

set -euo pipefail

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash

bash -i -c 'nvm install 20'

bash -i -c 'corepack enable'
