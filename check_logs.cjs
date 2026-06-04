const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
conn.on('ready', () => {
  conn.exec('tail -n 250 /home/cristian/apps/ecotop/storage/logs/laravel.log', (err, stream) => {
    if (err) throw err;
    let data = '';
    stream.on('close', (code, signal) => {
      fs.writeFileSync('error.txt', data);
      conn.end();
    }).on('data', (chunk) => {
      data += chunk;
    });
  });
}).connect({
  host: '167.86.72.200',
  port: 22,
  username: 'cristian',
  password: 'Cristian_5732988$'
});
