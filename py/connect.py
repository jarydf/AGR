import mysql.connector

connection = mysql.connector.connect(
  host = "localhost",
  user = "root",
  passwd = "",
  database = "agr"
)

print(connection)
