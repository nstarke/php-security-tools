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
egrep -r --include "*.php" -e "(mysql_connect|mysqli)\((\"|\').+(\"|\')\,\s+(\"|\').+(\"|\')\,\s+(\"|\').+(\"|\')" .

# this command will return potential unsafe SQL query executions:
egrep -r --include "*.php" -e "\-\>(query|exec)\(" .

# this command will return all PHP files in a directory for file system access
egrep -r --include "*.php" -e "fopen\(|fread\(|fwrite\(|fclose\(" .

# this command will return instances where crypto operations are performed
egrep -r --include "*.php" -e "mcrypt_|openssl_|mhash_|random_|crack_" .

# this command will return instances of weak PRNG's
# look for hard coded seed values!
egrep -r --include "*.php" -e "mt_srand\(|lcg_value\(|rand\(" .

# this command will return instances where XXE might be possible
# look for 'true'
egrep -r --include "*.php" -e "libxml_disable_entity_loader\(" .
