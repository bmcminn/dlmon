require('dotenv-safe').load();

const exec = require('child_process').exec;



exec('ipconfig', function(err, stdout, stderr) {
    if (err) {
        console.error(err);
        return;
    }

    let ipAddress = stdout.match(/IPv4 Address(?:\.\s)+:\s*((\d{1,3}\.?)+)/)[1].trim();

    console.log(ipAddress);

    exec(`set IPADDRESS=${ipAddress}:${process.env.PORT}`, function(err, stdout, stderr) {
        if (err) {
            console.error(err);
            return;
        }

    });
});
