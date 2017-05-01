# .bashrc

# User specific aliases and functions
alias php5='/usr/local/php5.6/bin/php -c /usr/local/php5.6/etc/php.ini'
alias composer='php5 ~/opt/bin/composer.phar'
alias art='php5 ./artisan'

alias gss='git status -s'

# Source global definitions
if [ -f /etc/bashrc ]; then
        . /etc/bashrc
fi
