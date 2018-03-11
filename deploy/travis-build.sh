#!/bin/bash
set -e # Exit with nonzero exit code if anything fails

SOURCE_BRANCH="master"

# Pull requests and commits to other branches shouldn't try to deploy, just build to verify
if [ "$TRAVIS_PULL_REQUEST" != "false" -o "$TRAVIS_BRANCH" != "$SOURCE_BRANCH" ]; then
	echo "Skipping deploy; just doing a build." >&2
	make
	exit 0
fi

# Save some useful information
export SHA=`git rev-parse --verify HEAD`

eval `ssh-agent -s`
ssh-add deploy/deploy_key

make autobuild
