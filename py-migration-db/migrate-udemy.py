import mysql.connector

# Establish a connection to the MySQL server
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="root",
    database="exam"
)

