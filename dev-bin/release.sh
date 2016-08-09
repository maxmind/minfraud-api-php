#!/bin/bash

set -e

VERSION=$(perl -MFile::Slurp::Tiny=read_file -MDateTime <<EOF
use v5.16;
my \$log = read_file(q{CHANGELOG.md});
\$log =~ /\n(\d+\.\d+\.\d+) \((\d{4}-\d{2}-\d{2})\)\n/;
die 'Release time is not today!' unless DateTime->now->ymd eq \$2;
say \$1;
EOF
)

TAG="v$VERSION"

if [ -f minfraud.phar ]; then
    rm minfraud.phar
fi

if [ -n "$(git status --porcelain)" ]; then
    echo ". is not clean." >&2
    exit 1
fi

if [ -d vendor ]; then
    rm -fr vendor
fi

php composer.phar self-update
php composer.phar update --no-dev

perl -pi -e "s/(?<=const VERSION = ').+?(?=';)/$TAG/g" src/MinFraud.php

if [ ! -f box.phar ]; then
    wget -O box.phar "https://github.com/box-project/box2/releases/download/2.6.1/box-2.6.1.phar"
fi

php box.phar build

PHAR_TEST=$(./dev-bin/phar-test.php)
if [[ -n $PHAR_TEST ]]; then
    echo "Phar test outputed non-empty string: $PHAR_TEST"
    exit 1
fi

# Download test deps
php composer.phar update

./vendor/bin/phpunit

if [ ! -d .gh-pages ]; then
    echo "Checking out gh-pages in .gh-pages"
    git clone -b gh-pages git@github.com:maxmind/minfraud-api-php.git .gh-pages
    pushd .gh-pages
else
    echo "Updating .gh-pages"
    pushd .gh-pages
    git pull
fi

if [ ! -d .geoip2 ]; then
    echo "Cloning GeoIP2 for docs"
    git clone git@github.com:maxmind/GeoIP2-php.git .geoip2
else
    echo "Updating GeoIP2 for docs"
    pushd .geoip2
    git pull
    popd
fi

if [ -n "$(git status --porcelain)" ]; then
    echo ".gh-pages is not clean" >&2
    exit 1
fi

# We no longer have apigen as a dependency in Composer as releases are
# sporadically deleted upstream and compatibility is often broken on patch
# releases.
wget -O apigen.phar "http://apigen.org/apigen.phar"

php apigen.phar generate \
    -s ../src \
    -s .geoip2 \
    -d "doc/$TAG" \
    --title "minFraud Score, Insights, and Factors PHP API $TAG" \
    --template-theme bootstrap \
    --exclude "Compat" \
    --php

php apigen.phar generate


PAGE=index.md
cat <<EOF > $PAGE
---
layout: default
title: minFraud Score and Insights PHP API
language: php
version: $TAG
---

EOF

cat ../README.md >> $PAGE

git add doc/

read -e -p "Commit changes and push to origin? " SHOULD_PUSH

if [ "$SHOULD_PUSH" != "y" ]; then
    echo "Aborting"
    exit 1
fi

git commit -m "Updated for $TAG" -a
git push

popd

git commit -m "Update for $TAG" -a

git tag -a "$TAG"
git push
git push --tags
