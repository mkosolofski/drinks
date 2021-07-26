until eval "cd /var/www/Api && bin/console doctrine:database:create && bin/console doctrine:migration:migrate --no-interaction"; do
  >&2 echo "mysql is unavailable - sleeping"
  sleep 1
done

exec supervisord -n -c /etc/supervisor/supervisord.conf
