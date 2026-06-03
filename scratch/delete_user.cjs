const { Client } = require('ssh2');

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Client Ready');

  const command = `cd /home/cristian/apps/ecotop && php artisan tinker --execute="\\$u = App\\Models\\User::where('email', 'cmarcelasl6519@gmail.com')->first(); if(\\$u) { App\\Models\\UserScore::where('user_id', \\$u->id)->delete(); \\$u->delete(); echo 'Usuario y puntajes eliminados exitosamente.'; } else { echo 'Usuario no encontrado.'; } echo \\"\\n\\";"`;

  conn.exec(command, (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      conn.end();
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
