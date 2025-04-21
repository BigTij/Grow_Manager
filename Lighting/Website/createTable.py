#---------------------------------#
# If this script doesn't work     #
# verify grants for user in mysql #
# server config                   #
#---------------------------------#

import mysql.connector

nb_of_lights = 4

mydb = mysql.connector.connect(
    host= "localhost",
    user = "root",
    password = "Tu63_gQl3+90",
    database = "Lighting"
)
cursor2 = mydb.cursor()

for i in range(nb_of_lights):
    sql = "CREATE TABLE IF NOT EXISTS Light_" + str(i + 1) + "_Schedule (date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, time_on TINYINT NOT NULL, time_off TINYINT NOT NULL);";
    cursor = mydb.cursor()
    cursor.execute(sql)
    cursor.close()

sql = "create table if not exists State_VEG_ROOM_01 (Date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
for i in range(nb_of_lights):
    if((i + 1) != nb_of_lights):
        sql += "Light" + str(i + 1) + " TINYINT, "

    else:
        sql += "Light" + str(i + 1) + " TINYINT);"


cursor2.execute(sql)
cursor2.close()

mydb.close()
