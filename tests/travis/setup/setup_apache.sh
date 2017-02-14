echo "Install and setup apache"
sudo apt-get update > /dev/null
sudo apt-get -qq install -y --force-yes apache2 libapache2-mod-fastcgi
# sudo apt-get -qq install -y --force-yes apache2 libapache2-mod-php5 php5-curl php5-intl php5-gd php5-idn php-pear php5-imagick php5-imap php5-mcrypt php5-memcache php5-ming php5-ps php5-pspell php5-recode php5-snmp php5-sqlite php5-tidy php5-xmlrpc php5-xsl php5-mysql uopz
sudo a2enmod rewrite

## Setting the home directory for localhost.
# convert_selenium_to_phpunit.sh assumes that the files are in a folder with this pattern: */svn/*/trunk
# * can be a folder or nothing.
# This is done because of the local folder structure, which I don't want to change.
# sudo sed -i -e "s,/var/www,/home/travis/build/dasscheman/Hike3_development,g" /etc/apache2/sites-available/default
# sudo sed -i -e "s,AllowOverride[ ]None,AllowOverride All,g" /etc/apache2/sites-available/default
#
# sudo /etc/init.d/apache2 restart

sudo cp -f build/travis-ci-apache /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default
sudo service apache2 restart
## create the table in the database.
mysql -u root --password=password hike_v3_01_test < ./tests/codeception/_data/hike-v2-01.sql;
