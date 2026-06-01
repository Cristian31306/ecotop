const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Client Ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastGet('/home/cristian/apps/ecotop/storage/logs/laravel.log', 'vps_laravel.log', (err) => {
      if (err) console.error('Error downloading laravel.log:', err.message);
      else console.log('Downloaded vps_laravel.log');
      conn.end();
    });
  });
}).connect({
  host: '167.86.72.200',
  port: 22,
  username: 'cristian',
  password: 'Cristian_5732988$'
});
