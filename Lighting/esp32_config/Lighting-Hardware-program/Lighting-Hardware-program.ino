#include <WiFi.h>
#include <MySQL_Connection.h>
#include <WiFiClient.h>
#include <MySQL_Cursor.h>
#include <stdio.h>

char mysqlUser[] = "esp32-VEG_ROOM_01";
char mysqlPWD[] = "Tu63_gQl3+90";
char db[] = "Lighting";

char* ssid = "Lussier";
char* pwd = "wrh22930";

const int RELAY_1_pin = 1;
const int RELAY_2_pin = 2;
const int RELAY_3_pin = 3;
const int RELAY_4_pin = 4; 

int actual_time = 0; // in seconds
int time_on_1 = 0; 
int time_off_1 = 0;
int time_on_2 = 0;
int time_off_2 = 0;
int time_on_3 = 0;
int time_off_3 = 0;
int time_on_4 = 0;
int time_off_4 = 0;

int state1 = 0;
int state2 = 0;
int state3 = 0;
int state4 = 0;

WiFiClient client;
IPAddress MySQL_server(10,0,0,14);

// Start tcp server on port 5045:
WiFiServer server(5045);
MySQL_Connection conn((Client *)&client);

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);

  WiFi.disconnect(); // Prevent connecting to wifi based on 
                     // previous configuration
                                                                                                                                                                                                                                           
  
  IPAddress local_IP(10,0,0,19);
  IPAddress gateway(10,0,0,1);
  IPAddress subnet(255,255,0,0);
  IPAddress primaryDNS(1,1,1,1);
  IPAddress secondaryDNS(1,0,0,1);
  
  WiFi.config(local_IP,subnet,gateway,primaryDNS);
  
  WiFi.begin(ssid,pwd);
  Serial.println();
  
  //Wait for connection
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }
  Serial.print("Connected to "); Serial.println(ssid);
  Serial.print("IP Address: "); Serial.println(WiFi.localIP());

  // Start the TCP server
  server.begin();
  
  pinMode(RELAY_1_pin,OUTPUT);
  pinMode(RELAY_2_pin,OUTPUT);
  pinMode(RELAY_3_pin,OUTPUT);
  pinMode(RELAY_4_pin,OUTPUT);

  Update_relay(RELAY_1_pin,false);
  Update_relay(RELAY_2_pin,false);
  Update_relay(RELAY_3_pin,false);
  Update_relay(RELAY_4_pin,false);
}

void Update_relay(int relay_pin, bool flag){
  digitalWrite(relay_pin,flag);
}
char* intToStr(int nb){
  char buff[5];
  itoa(nb, buff, 10); // 10 for decimal
}
void Save_state(){
   char INSERT_DATA[80];
   char table_name[] = "State_VEG_ROOM_ 01";


   strcat(INSERT_DATA, "INSERT INTO ");
   strcat(INSERT_DATA, db);
   strcat(INSERT_DATA, " . ");
   strcat(INSERT_DATA, table_name);
   strcat(INSERT_DATA, " (Light1, Light2, Light3, Light4) VALUES ('");
   strcat(INSERT_DATA, intToStr(state1));
   strcat(INSERT_DATA, "','");
   strcat(INSERT_DATA, intToStr(state2));
   strcat(INSERT_DATA, "','");
   strcat(INSERT_DATA, intToStr(state3));
   strcat(INSERT_DATA, "','");
   strcat(INSERT_DATA, intToStr(state4));
   strcat(INSERT_DATA, "');");
   
   Serial.print("Command: "); Serial.println(INSERT_DATA);

   if(conn.connect(MySQL_server,3306,mysqlUser,mysqlPWD)){
    delay(1000);
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(INSERT_DATA);
    delete cur_mem;
  }
  else{
    Serial.println("Failed to send data to mysql");
  }
  conn.close();
}

void Save_schedule(int id, char* time_on, char* time_off){
   char INSERT_DATA[80];
   char table_name[] = "Light_";

   strcat(table_name, intToStr(id));
   strcat(table_name, "-Schedule"); 

   strcat(INSERT_DATA, "INSERT INTO ");
   strcat(INSERT_DATA, db);
   strcat(INSERT_DATA, " . ");
   strcat(INSERT_DATA, table_name);
   strcat(INSERT_DATA, " (time_on, time_off) VALUES ('");
   strcat(INSERT_DATA, time_on);
   strcat(INSERT_DATA, "','");
   strcat(INSERT_DATA, time_off);
   strcat(INSERT_DATA, "');");

   Serial.print("Command: "); Serial.println(INSERT_DATA);

   if(conn.connect(MySQL_server,3306,mysqlUser,mysqlPWD)){
    delay(1000);
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(INSERT_DATA);
    delete cur_mem;
  }
  else{
    Serial.println("Failed to send data to mysql");
  }
  conn.close();
}

void Validate_command(char *command){ // command format ex: "r1 72000 28800"
  bool valid_command = true;
  char *copy = "";
  strcpy(copy, command);
  char *ptr = strtok(copy, " ");
  
  int counter = 0;
  while(ptr != NULL){
    counter++;
  }

  char *commands[counter];
  ptr = strtok(command, " ");
  counter = 0;
  while(ptr != NULL){
    commands[counter] = ptr;
    counter++;
  }
  
  if(commands[0] == "r1" || commands[0] == "r2" || commands[0] == "r3" || commands[0] == "r4"){
    if(atoi(commands[1]) != NULL && atoi(commands[2]) != NULL){
      if(commands[0] == "r1"){
        time_on_1 = atoi(commands[1]);
        time_off_1 = atoi(commands[2]);
        Save_schedule(1,commands[1],commands[2]);
      }
      else if(commands[0] == "r2"){
        time_on_2 = atoi(commands[1]);
        time_off_2 = atoi(commands[2]);
        Save_schedule(2,commands[1],commands[2]);
      }
      else if(commands[0] == "r3"){
        time_on_3 = atoi(commands[1]);
        time_off_3 = atoi(commands[2]);
        Save_schedule(3,commands[1],commands[2]);
      }
      else{
        time_on_4 = atoi(commands[1]);
        time_off_4 = atoi(commands[2]);
        Save_schedule(4,commands[1],commands[2]);
      }
      valid_command = true;
    }
    else{
      valid_command = false;
    }
  }
  else{
    valid_command = false;
  }
  if(valid_command){
    Serial.print("This command is valid: ");
    Serial.println(command);
  }
  else{
    Serial.print("This command is invalid: ");
    Serial.println(command);
  }
}

void TCP_server(){
  WiFiClient client = server.available();
  if(client){
    Serial.println("Client connected");
    int counter = 0;
    char Buffer[30];
    while(client.connected()){
      if(counter < 30){
        Buffer[counter] = client.read();
      }
      else{
        Serial.println("Buffer overflow, connection closed.");
      }
      counter++;
    }
    Serial.print("Command received: ");
    Serial.println(Buffer);
    char *command = Buffer;

    Validate_command(command);
  }
}

void loop() {
  // put your main code here, to run repeatedly:
  TCP_server();

// Relay pin management

  if(actual_time == time_on_1){
    Update_relay(RELAY_1_pin, true);
    state1 = 1;
  }
  else if(actual_time == time_off_1){
    Update_relay(RELAY_1_pin, false);
    state1 = 0;
  }
  if(actual_time == time_on_2){
    Update_relay(RELAY_2_pin, true);
    state2 = 1;
  }
  else if(actual_time == time_off_2){
    Update_relay(RELAY_2_pin, false);
    state2 = 0;
  }
  if(actual_time == time_on_3){
    Update_relay(RELAY_3_pin, true);
    state3 = 1;
  }
  else if(actual_time == time_off_3){
    Update_relay(RELAY_3_pin, false);
    state3 = 0;
  }
  if(actual_time == time_on_4){
    Update_relay(RELAY_4_pin, true);
    state4 = 1;
  }
  else if(actual_time == time_off_4){
    Update_relay(RELAY_4_pin, false);
    state4 = 0;
  }
  
  if(actual_time % 3600 == 0){ // every hour:
    Save_state();
  }
  if(actual_time == 86400){
    actual_time = 0;
  }
  else{
    actual_time++;
  }
  Serial.print("Actual time: ");
  Serial.println(actual_time);
  delay(1000);
}
