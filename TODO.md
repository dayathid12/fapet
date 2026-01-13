# Deployment Preparation Steps

- [x] Install Composer dependencies for production: composer install --optimize-autoloader --no-dev
- [x] Install NPM dependencies: npm install
- [ ] Build frontend assets for production: npm run build
- [ ] Clear Laravel caches: php artisan optimize:clear
- [ ] Optimize Laravel (cache config, routes, views): php artisan optimize
- [ ] Create storage symlink: php artisan storage:link
- [ ] Run database migrations: php artisan migrate --force
- [x] Set permissions on storage directory: sudo chown -R www-data:www-data /path/to/your/project/storage (Skipped on Windows - sudo not available)
- [x] Set permissions on bootstrap/cache directory: sudo chown -R www-data:www-data /path/to/your/project/bootstrap/cache (Skipped on Windows - sudo not available)
- [ ] (Optional) Configure PM2 for production if needed
