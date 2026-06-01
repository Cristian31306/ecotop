const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Client Ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    
    sftp.fastPut('app/Http/Controllers/QuizController.php', '/home/cristian/apps/ecotop/app/Http/Controllers/QuizController.php', (err) => {
      if (err) console.error(err);
      else console.log('Uploaded QuizController.php');
      
      sftp.fastPut('resources/js/Pages/Quiz/Show.vue', '/home/cristian/apps/ecotop/resources/js/Pages/Quiz/Show.vue', (err) => {
        if (err) console.error(err);
        else console.log('Uploaded Show.vue');
        
        // Run npm build on the VPS
        conn.exec('cd /home/cristian/apps/ecotop && npm run build', (err, stream) => {
          if (err) throw err;
          stream.on('close', () => {
            console.log('Build done');
            conn.end();
          }).on('data', (d) => process.stdout.write(d));
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
