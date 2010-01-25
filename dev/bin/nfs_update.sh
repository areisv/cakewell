#!/bin/bash

###
#   nfs_update.sh
#   Update Cakewell CakePhp code on nearlyfreespeech.net
#
# USAGE:
#   nfs_update.sh NFS_USER
#
# NOTES:
#   GID = Global ID for script (used in creating tmp dirs)
#   LPD = Local Project Dir
#   tmp dir on NFS is mapped to /home/tmp
###

# -------------------- #
# Global Settings

# Local Server
GID='cakewell'
LPD=~/qed/cakewell
TODAY=`date +%Y%m%d`
LWD=$LPD/tmp-install-$TODAY

# Ignore these repository directories
IGNORE_ZIP="\
app/tests
app/tmp
app/config/*.default
app/config/phpmailer.php
app/config/sql
app/config/modes/test*
app/config/domains/my-domain.com.php
app/config/domains/cakewell.php
app/config/domains/localhost.php
"

# Remote Server
VPS_HOST=ssh.phx.nearlyfreespeech.net
RWD=/tmp/$GID-$TODAY
REMOTE_APP_DIR=/home/protected
REMOTE_WEB_DIR=/home/public

# Non-editable
VPS_USER=''
SCRIPT=$(readlink -f $0)
SCRIPTDIR=`dirname $SCRIPT`
ARGS=($@)
# -------------------- #

function parse_commandline() {
    if [ ${#ARGS[@]} -lt 1 ]; then
        usage
        exit_ "[local] You must pass the remote (ssh) user"
    else
        VPS_USER=${ARGS[0]}
        echo "[local] remote user set to $VPS_USER"
    fi
}

function create_lwd() {
    echo "[local] creating Local Working Directory (LWD) at $LWD"
    if [ ! -d $LWD ]; then
        mkdir $LWD
    fi
}

function gzip_app() {
    # create ignore list
    cat <<EOF > $LWD/IGNORE_ZIP
$IGNORE_ZIP
EOF
    cd $LPD
    tar cfz $LWD/$GID-$TODAY.tgz -X $LWD/IGNORE_ZIP app webroot

    # test file created
    if [ ! -e $LWD/$GID-$TODAY.tgz ]; then
        exit_ "[local] failed to create upload archive"
    fi

    # test file size
    ZIP_SIZE=$(stat -c%s "$LWD/$GID-$TODAY.tgz")
    KB_SIZE=$(($ZIP_SIZE/1000))
    if [ $KB_SIZE -lt 10 ]; then
        exit_ "[local] archive less than 10kb -- something probably wrong"
    else
        echo "[local] $GID archive created [$KB_SIZE kb]"
    fi
}

function upload_app() {
    echo "[local] copying (scp) archive to remote host: $VPS_HOST:/tmp"
    echo "[note] you may have to enter your password for remote user $VPS_USER"
    scp $LWD/$GID-$TODAY.tgz $VPS_USER@$VPS_HOST:/tmp
}

function install_app() {
    ssh $VPS_USER@$VPS_HOST <<EOC

        echo "[$VPS_HOST] creating remote working dir $RWD"
        if [ ! -d $RWD ]; then
            mkdir $RWD
        fi

        echo "[$VPS_HOST] backing up current cakewell code to $RWD/rollback"
        cd $RWD
        mkdir -p rollback/webroot
        cp -R $REMOTE_APP_DIR/app rollback
        cp -R $REMOTE_WEB_DIR/* rollback/webroot

        echo "[$VPS_HOST] unzipping archive"
        mkdir install
        mv /tmp/$GID-$TODAY.tgz install
        cd install
        tar xf $GID-$TODAY.tgz
        if [ $? -ne 0 ]; then
            exit
            exit_ "[$VPS_HOST] tar extraction failure"
        elif [ ! -d $RWD/install/app ]; then
            exit
            exit_ "[$VPS_HOST] tar extraction failure: app dir not found"
        fi

        echo "[$VPS_HOST] installing cakewell code"
        cd $RWD
        cp -R $RWD/install/webroot/* $REMOTE_WEB_DIR
        cp -R $RWD/install/app $REMOTE_APP_DIR

        echo "[$VPS_HOST] removing cakephp tmp files"
        cd $REMOTE_APP_DIR/app
        find tmp -type f -exec rm {} \;

        echo "[$VPS_HOST] listing cakephp tmp files (should be none)..."
        find tmp -type f

        # exit
        echo "[$VPS_HOST] disconnecting from remote server"
        exit
EOC
    prompt_
}

function preamble() {
    cat <<EOB

*** uploading cakewell to NFS hosted site ***
*** http://code.google.com/p/cakewell/    ***
remote host:        $VPS_HOST
local working dir:  $LWD
remote working dir: $RWD

EOB
    prompt_
}

function cleanup() {
    cat <<EOB
[local] install complete
[note] final notes:
1. if you updated config files, those will have to be manually installed
2. if you made database changes, don't forget to apply them to the server
3. current production code was backed up to $RWD/rollback/webroot
4. finally, to clean up, simply run:
    [local]$ rm -Rv $LWD
    [local]$ ssh $VPS_USER@$VPS_HOST rm -Rv $RWD
    - or -
    [$VPS_HOST]$ rm -Rv $RWD

[local] script complete
EOB
}

function usage() {
    cat <<EOB
USAGE:
    $SCRIPT <NFS_USER>
    e.g. $SCRIPT cakewell
EOB
}

function prompt_() {
    read -s -n1 -p "Hit [ENTER] or [SPACE] to continue, any other key to quit " INPUT_
    if [ ! -e $INPUT_ ]; then
        echo -e "\nquitting now...\n"
        exit 1
    fi
    echo -e "\n"
}

function exit_() {
    if [ -n "$1" ]; then
        echo "[ERROR] $1"
    fi
    echo "[ERROR] exiting"
    exit 1
}



# Main
preamble
parse_commandline
create_lwd
gzip_app
upload_app
install_app
cleanup
exit 0
