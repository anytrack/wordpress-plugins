#!/usr/bin/env bash
set -e

pluginName=${1}
version=${2}

if [[ $# -lt 2 ]] ; then
    echo "Usage: ${0} [pluginName] [version]"
    exit 0
fi

svnPath="svn/${pluginName}"

mkdir -p svn
rm -rf "${svnPath}"

svn co "https://plugins.svn.wordpress.org/${pluginName}" "${svnPath}" -q
svn del "${svnPath}/assets/"*
svn del "${svnPath}/trunk/"*
svn del "${svnPath}/tags/${version}/"*

cp -a "./${pluginName}/assets/." "${svnPath}/assets"
cp -a "./${pluginName}/trunk/." "${svnPath}/trunk"
cp -a "./${pluginName}/trunk/." "${svnPath}/tags/${version}"


svn add "${svnPath}/assets/"*
svn add "${svnPath}/trunk/"*
svn add --parents "${svnPath}/tags/${version}/"*

svn ci "${svnPath}" -m "v${version}"
