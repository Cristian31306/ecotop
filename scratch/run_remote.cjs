const { Client } = require('ssh2');

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Client Ready');

  const commands = [
    'cd /home/cristian/apps/ecotop',
    'git config core.filemode false',
    'git reset --hard',
    'git pull origin main',
    'git status'
  ];

  conn.exec(commands.join(' && '), (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      console.log(`\nCommand closed with code ${code}`);
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
