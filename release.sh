#!/bin/bash

function bump_version {
	output=$(npm version ${release} --no-git-tag-version)
	version=${output:1}
	search='("version":[[:space:]]*").+(")'
	replace="\1${version}\2"
	
	sed -i ".tmp" -E "s/${search}/${replace}/g" "$1"
	rm "$1.tmp"
}

echo "Did you commit necessary changes? (y/n)"
read committed

if [ $committed == "n" ]; then
    echo "Please commit all necessary changes first."
    exit 1
fi

package_version="$(grep version package.json | awk -F \" '{print $4}')"

echo "Enter next version (current $package_version): "
read new_version

npm version $new_version

if [ $? -ne 0 ]; then
    echo "Please clean your working directory before releasing."
    exit 1
fi

echo "Building release...\n"
npm run build:prod