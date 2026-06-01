const { Client } = require('ssh2');

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Client Ready');

  const nginxConfig = `server {
    server_name ecotop.algorah.bond;
    root /home/cristian/apps/ecotop/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \\.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \\.(?:css|js|woff2?|svg|gif|png|jpe?g|webp|ico|json)$ {
        expires 1y;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }
}`;

  // Step 1: Upload Nginx config via SFTP
  conn.sftp((err, sftp) => {
    if (err) throw err;
    
    sftp.writeFile('/home/cristian/ecotop_nginx.conf', nginxConfig, (err) => {
      if (err) throw err;
      console.log('Nginx config uploaded to home directory via SFTP.');

      // Step 2: Execute deployment commands
      const commands = [
        'echo "=== Pulling latest changes ==="',
        'cd /home/cristian/apps/ecotop',
        'git pull origin main || git pull',
        'echo "=== Running Composer Install ==="',
        'composer install --no-dev --optimize-autoloader || composer install',
        'echo "=== Running NPM Install & Build ==="',
        'npm install && npm run build',
        'echo "=== Setting Permissions (www-data) ==="',
        'echo "Cristian_5732988$" | sudo -S chown -R cristian:www-data /home/cristian/apps/ecotop',
        'echo "Cristian_5732988$" | sudo -S chmod -R 775 /home/cristian/apps/ecotop/storage',
        'echo "Cristian_5732988$" | sudo -S chmod -R 775 /home/cristian/apps/ecotop/bootstrap/cache',
        'echo "Cristian_5732988$" | sudo -S chmod -R 775 /home/cristian/apps/ecotop/database',
        'echo "Cristian_5732988$" | sudo -S chmod 664 /home/cristian/apps/ecotop/database/database.sqlite',
        'echo "=== Creating storage symlink if not exists ==="',
        'php artisan storage:link || echo "Storage link already exists"',
        'echo "=== Running Migrations ==="',
        'php artisan migrate --force',
        'echo "=== Clearing Laravel Cache ==="',
        'php artisan config:clear',
        'php artisan cache:clear',
        'echo "=== Copying Nginx Config to sites-available ==="',
        'echo "Cristian_5732988$" | sudo -S mv /home/cristian/ecotop_nginx.conf /etc/nginx/sites-available/ecotop',
        'echo "Cristian_5732988$" | sudo -S chown root:root /etc/nginx/sites-available/ecotop',
        'echo "=== Enabling Nginx Site ==="',
        'echo "Cristian_5732988$" | sudo -S ln -sf /etc/nginx/sites-available/ecotop /etc/nginx/sites-enabled/ecotop',
        'echo "=== Testing Nginx Config ==="',
        'echo "Cristian_5732988$" | sudo -S nginx -t',
        'echo "=== Reloading Nginx ==="',
        'echo "Cristian_5732988$" | sudo -S systemctl reload nginx',
        'echo "=== Requesting SSL with Certbot ==="',
        'echo "Cristian_5732988$" | sudo -S certbot --nginx -d ecotop.algorah.bond --non-interactive --agree-tos -m durancristian31306@gmail.com --redirect || echo "Certbot failed or SSL already configured"',
        'echo "=== Final Nginx Reload ==="',
        'echo "Cristian_5732988$" | sudo -S systemctl reload nginx',
        'echo "=== Deploy Finished successfully! ==="'
      ];

      conn.exec(commands.join(' && '), (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
          console.log(`Command closed with code ${code}`);
          conn.end();
        }).on('data', (data) => {
          process.stdout.write(data);
        }).stderr.on('data', (data) => {
          process.stderr.write(data);
        });
      });
    });
  });
}).connect({
  host: '167.86.72.200',
  port: 22,
  username: 'cristian',
  password: 'Cristian_5732988$'
});
