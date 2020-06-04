const banco = require("./database/configdatabase");
const fs = require('fs');
const path = require('path');

const APP = async () => {
    var pastas = [];
    let log = [];
    let logExec = [];
    console.log('INICIANDO SYNC>>>>>>>>>>>>>>>>>>');
    logExec.push('INICIANDO A SYNC')
    let pasta = path.resolve(__dirname, "SCRIPTS");
    console.log(pasta);
    fs.readdirSync(pasta).forEach(async f => pastas.push(f));
    logExec.push(pastas);
    console.log(pastas);
    pastas = await VerificaUltimaSyncronizacao(pastas);
    await pastas.forEach((p, i) => {
        fs.readdirSync(path.resolve(__dirname, "SCRIPTS", p)).forEach(async file => {
            try {
                await VerificaArquivo(p, file, log);
            } catch (err) {
                log.push(err);
            }
            await GerarLogeExecucao(logExec,p);
            await GerarLog(log, p);
        });
    });
    await SalvaUltimaSync();
    await console.log('Fim');
    setTimeout(()=>{
        process.exit();
    },10000);

    async function VerificaArquivo(p, file, listLog) {
        let query = fs.readFileSync(path.resolve(__dirname, "SCRIPTS", p, file)).toLocaleString();
        query = query.split(';');
        query.forEach((item, i) => {
            if (item == "" || item == "\r\n")
                query.splice(i, 1);
        });
         for (let index = 0; index < query.length; index++) {
            try {
                let aply = await banco.query(`${query[index]}`);
                logExec.push(`${aply.serverStatus == '2' ? "[OK]":"[WRN]"}-${query[index].trim()}`);
            }
            catch (err) {
                listLog.push(err);
            }
        }
    }

    async function VerificaUltimaSyncronizacao(pastasArray = []) {
        let localpastasArray = pastasArray;
        try {
            let scripts = await banco.query('SELECT * FROM Sincronizador order by data asc');
            SalvaUltimaSync();
            scripts = await banco.query('SELECT * FROM Sincronizador order by data asc');
            if (scripts[0][scripts[0].length - 1].script != undefined) {

                await localpastasArray.forEach((item, i) => {
                    if (item == scripts[0][scripts[0].length - 1].script)
                        localpastasArray.splice(0, i == 0 ? 1 : i);
                });
            }
        }

        catch (error) {

            console.log("Tabela Sincronizacao Sendo Criada");
            let re = await banco.query("CREATE TABLE IF NOT EXISTS Sincronizador(id int(11) auto_increment Primary Key not null, data timestamp default current_timestamp,script varchar(255) not null)");
            if (re[0])
                console.log("Tabela Sincronizacao Criada");
        }

        return localpastasArray;
    }
    async function SalvaUltimaSync() {
        let localpastasArray = [];
        await fs.readdirSync(pasta).forEach(async f => localpastasArray.push(f));
        if (localpastasArray.length > 1) {
            let scriptsJaexecutados = await banco.query(`select * from Sincronizador where script like ? `, [localpastasArray[localpastasArray.length - 2]]);
            if (scriptsJaexecutados[0].length > 0) {
                await banco.query(`update Sincronizador set data = current_timestamp where id = ? `, [scriptsJaexecutados[0][0].id])
            } else
                await banco.query(`insert into Sincronizador (script) values(?)`, [localpastasArray[localpastasArray.length - 2]]);
        }
    };
   async function GerarLog(LogsList = [], p) {
        pathLog = path.resolve(__dirname, "..", "logs");
        let criarlog = fs.createWriteStream(`${pathLog}/Log${p}.txt`);
        await LogsList.forEach(l => {
            criarlog.write(`${new Date().toLocaleDateString('pt-BR')} 
                        ${new Date().toLocaleTimeString()} #` + l + '\t');
            console.log(`${new Date().toLocaleDateString('pt-BR')} 
                     ${new Date().toLocaleTimeString()} #` + l + '\t');
        });
        criarlog.end();
        console.log('Arquivo de Log Criado em: ' + path.resolve('Log.txt'));

    }
    async function GerarLogeExecucao(LogsList = [], p) {
        pathLog = path.resolve(__dirname, "..", "logs");
        let criarlog = fs.createWriteStream(`${pathLog}/LogRun${p}.txt`);
        await LogsList.forEach(l => {
            criarlog.write(`${new Date().toLocaleDateString('pt-BR')}
            ${new Date().toLocaleTimeString()} #` + l + '\n');
        });
        criarlog.end();
        console.log('Arquivo de Log Criado em: ' + path.resolve(`LogRun${p}.txt`));

    }


};
APP();
