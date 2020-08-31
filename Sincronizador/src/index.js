const { connection: banco, database } = require("./database/configdatabase");
const fs = require('fs');
const path = require('path');
const { resolve } = require("path");

const APP = async () => {

    var pastas = [];
    let log = [];
    let logExec = [];
    console.log("\x1b[41m","\x1b[37m","\x1b[5m",'>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>INICIANDO SYNC>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
    logExec.push('INICIANDO A SYNC')
    let pasta = path.resolve(__dirname, "SCRIPTS");
    fs.readdirSync(pasta).map(async f => pastas.push(f));
    logExec.push(pastas);
    console.log("Versões Encontradas:","\x1b[32m",pastas);
    pastas = await VerificaUltimaSyncronizacao(pastas);
    for (const p of pastas) {
        console.log("\x1b[35m",'INICIANDO Sql',"\x1b[32m",`${p}`)
        const ListaFile = fs.readdirSync(path.resolve(__dirname, "SCRIPTS", p));
        for (const file of ListaFile) {
            try {
                await VerificaArquivo(p, file, log);
            } catch (err) {
                log.push(err);
            }
            GerarLogeExecucao(logExec, p);
            GerarLog(log, p);
        }
        console.log("\x1b[35m",'FIM Sql',"\x1b[32m",`${p}`)

    };
    SalvaUltimaSync();
    console.log("\x1b[41m","\x1b[37m","\x1b[5m",'-------------------------------------Fim------------------------------------');
    process.exit();
    // setTimeout(() => {
    //    
    // }, 10000);

    async function VerificaArquivo(p, file, listLog) {

        let query = fs.readFileSync(path.resolve(__dirname, "SCRIPTS", p, file)).toLocaleString();
        query = query.split(';');
        query.map((item, i) => {
            if (item == "" || item == "\r\n"|| item == '')
                query.splice(i, 1);
            if (/--/.exec(item) != null)
                query.splice(i, 1);
            if (/[$][$][D][B][N][A][M][E][$][$]/.exec(item) != null)
                query[i] = query[i].replace(/[$][$][D][B][N][A][M][E][$][$]/, database);
        });

        for (const item of query) {
            try {
                let aply = await banco.query(`${item}`);
                logExec.push(`${aply[0].serverStatus == 2 ? "[OK]" : "[WRN]"}-${item.trim()}`);
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

            console.log("\x1b[32m","Tabela Sincronizacao Sendo Criada");
            let re = await banco.query("CREATE TABLE IF NOT EXISTS Sincronizador(id int(11) auto_increment Primary Key not null, data timestamp default current_timestamp,script varchar(255) not null)");
            if (re[0]) {
                console.log("\x1b[32m","Tabela Sincronizacao Criada");
                return localpastasArray;
            }
        }

        return localpastasArray;
    }
    async function SalvaUltimaSync() {
        let localpastasArray = [];
        fs.readdirSync(pasta).forEach(async f => localpastasArray.push(f));
        if (localpastasArray.length > 1) {
            let scriptsJaexecutados = await banco.query(`select * from Sincronizador where script like ? `, [localpastasArray[localpastasArray.length - 2]]);
            if (scriptsJaexecutados[0].length > 0) {
                await banco.query(`update Sincronizador set data = current_timestamp where id = ? `, [scriptsJaexecutados[0][0].id])
            } else
                await banco.query(`insert into Sincronizador (script) values(?)`, [localpastasArray[localpastasArray.length - 2]]);
        }
    };
    function GerarLog(LogsList = [], p) {
        pathLog = path.resolve(__dirname, "..", "logs");
        let criarlog = fs.createWriteStream(`${pathLog}/Log${p}.txt`);
        LogsList.forEach(l => {
            criarlog.write(`${new Date().toLocaleDateString('pt-BR')}:${new Date().toLocaleTimeString()} #` + l );
            console.log( "\x1b[31m",`${new Date().toLocaleDateString('pt-BR')}:${new Date().toLocaleTimeString()} #` + l);
        });
        criarlog.end();
        console.log("\x1b[32m",'Arquivo de Log Criado em: ' + path.resolve('Log.txt'));

    }
    function GerarLogeExecucao(LogsList = [], p) {
        pathLog = path.resolve(__dirname, "..", "logs");
        let criarlog = fs.createWriteStream(`${pathLog}/LogRun${p}.txt`);
        LogsList.forEach(l => {
            criarlog.write(`${new Date().toLocaleDateString('pt-BR')}:${new Date().toLocaleTimeString()} #` + l + '\n');
        });
        criarlog.end();
        console.log("\x1b[32m",'Arquivo de LogExecucao Criado em: ' + path.resolve(`LogRun${p}.txt`));

    }


};
APP();
