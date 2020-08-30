const STARTUP = require("../../../STARTUP");
const mysql = require('mysql2/promise');

let connSemBD = mysql.createPool({
    host: STARTUP.databasehost,
    user: 'root',
    password: ''
});
const CriaCon = async () => {
    await connSemBD.query(`CREATE DATABASE IF NOT EXISTS ${STARTUP.database}`);
    return connection;
}
CriaCon();
var connection = mysql.createPool({
    host: STARTUP.databasehost,
    user: 'root',
    password: '',
    database: STARTUP.database
});
module.exports = {connection,database:STARTUP.database};
