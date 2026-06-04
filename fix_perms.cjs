const { Client } = require('ssh2');
const conn = new Client();
conn.on('ready', () => {
  conn.exec('echo "Cristian_5732988$" | sudo -S chown -R www-data:www-data /home/cristian/apps/ecotop/database && echo "Cristian_5732988$" | sudo -S chmod -R 775 /home/cristian/apps/ecotop/database', (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      conn.end();
      console.log('Permissions fixed!');
    }).on('data', (data) => {
      process.stdout.write(data);
    }).stderr.on('data', (data) => {
      process.stderr.write(data);
    });
  });
}).connect({
  host: '167.86.72.200',
  port: 22,
  username: 'cristian',
  password: 'Cristian_5732988$'
});
