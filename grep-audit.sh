# These are a collection of egrep commands that may be useful to penetration testers
# working in a PHP environment, auditing source code.

# this command searches all PHP files in a directory for vulnerable shell functions
egrep -r --include "*.php" -e "system\(|exec\(|popen\(|pcntl_exec\(|proc_open\(" .

# this command searches all PHP files in a directory for certain vulnerable php execution functions
egrep -r --include "*.php" -e "eval\(|assert\(|preg_replace\(" .

# this command returns instances where variables are echoed out without htmlspecialchars()
# it can be useful for finding XSS vulnerabilities in PHP code
egrep -r --include "*.php" -e "echo \$" .

# this command returns all instances of the back-tick (`) operator, which is used to execute arbitary shell commands in PHP
# many times this returns string literals
egrep -r --include "*.php" -e "\`" .

# this command will return hard-coded database credentials / addresses
egrep -r --include "*.php" -e "mysql_connect\((\"|\').+(\"|\')\,\s+(\"|\').+(\"|\')\,\s+(\"|\').+(\"|\')" .

# this command will return potential unsafe SQL query executions:
egrep -r --include "*.php" -e "\-\>query\(|\->\>exec\(" .

# this command will return all PHP files in a directory for file system access
egrep -r --include "*.php" -e "fopen\(|fread\(|fwrite\(|fclose\(" .
