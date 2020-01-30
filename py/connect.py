import mysql.connector

connection = mysql.connector.connect(
  host = "localhost",
  user = "gaugeReaders",
  passwd = "Vision_Killers",
  database = "AGR"
)

print(connection)
