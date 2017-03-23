Deployment of Sovafrem platform
-------------------------------

OVH hosting doesn't have access to internet directly. Internet access is only allowed through PHP.

So the process of deployment is the following:

- Push to copy of git repo present on hosting named `sovafrem_repo`
- Hook script `post-receive` making other folders checked out to pull from local repo `sovafrem_repo`
- Execute `composer`


Also the basic command `php` is using PHP 4 by default. So we need to add an alias `php5` to make command use another `.ini` provided by OVH.

`Composer.phar` is installed locally in `~/opt/bin` and aliased to `composer`.

### Cheat sheet

To create a bare repo: e.g. `git init --bare ~/marctanguy/sovafrem.git`

To clone a local repo (files are hard-linked to save space): e.g. `git -l ~/marctanguy/sovafrem_repo 7.sovafrem.com`

To add repo as a remote: `git remote add staging sovafrem@ftp.cluster015.ovh.net:marctanguy/sovafrem.git`
