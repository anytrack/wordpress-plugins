#!/usr/bin/env bash
set -e

pluginFolder=${1}
message=${2}

cd "svn/$pluginFolder"
cp -a "../../$pluginFolder/." "./trunk"
svn ci -m "$message"