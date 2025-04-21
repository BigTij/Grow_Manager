#include <WiFi.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include <SPI.h>
#include <Ethernet.h>
#include <time.h>
#include <stdio.h>
#include <driver/adc.h>

char* ssid     = "Lussier";
char* password = "wrh22930";
char* database_pwd = "Tu63_gQl3+90";
char* db = "SensorData";
char* table_name = "AeroponicSensor"; 
char* user = "root";

IPAddress server_addr(10,0,0,14);
WiFiClient client;
MySQL_Connection conn((Client *)&client);

// inputs
const int TDS_pin = 36;
const int PH_pin = 39;
const int SPRAYER_WATER_LEVEL_pin = 34;
const int TANK_WATER_LEVEL_pin = 35;
// outputs
const int PUMP_RELAY_pin = 21;
const int MIXER_RELAY_pin = 32;
const int SMALL_PUMP_RELAY_pin = 33;

const int MIN_VOLTAGE = 0;
const int MAX_VOLTAGE = 4095;

int pump_timer = 0;
int sensors_timer = 0;

// info for sending water level data to server
const uint16_t port = 10000;
const char * host = "10.0.0.14";
const char * localIp = "10.0.0.19";

byte mac[6];

bool tank_level_state = false;
bool spray_level_state = false;

void setup()
{
    Serial.begin(115200);
    delay(10);

    // We start by connecting to a WiFi network

    Serial.println();
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(ssid);

    IPAddress local_IP(10, 0, 0, 19);
    IPAddress gateway(10,0,0,1);
    IPAddress subnet(255,255,0,0);
    IPAddress primaryDNS(1,1,1,1);
    IPAddress secondaryDNS(1,0,0,1);

    WiFi.config(local_IP, gateway, subnet, primaryDNS, secondaryDNS);

    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    WiFi.macAddress(mac);
    Serial.println("");
    Serial.println("WiFi connected");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    Serial.print("MAC address: ");
    int i = 0;
    for(i = 0; i < 6; i++){
      if(i != 5){
        Serial.print(mac[i],HEX);
        Serial.print(":");
      }
      else{
        Serial.print(mac[i],HEX);
      }
    }

    adc1_config_width(ADC_WIDTH_12Bit);
    adc1_config_channel_atten(ADC1_CHANNEL_6, ADC_ATTEN_DB_11);
    
    pinMode(TDS_pin, INPUT);
    pinMode(PH_pin, INPUT);
    pinMode(SPRAYER_WATER_LEVEL_pin, INPUT);
    pinMode(TANK_WATER_LEVEL_pin, INPUT);

    pinMode(SMALL_PUMP_RELAY_pin, OUTPUT);
    pinMode(PUMP_RELAY_pin, OUTPUT);
    pinMode(MIXER_RELAY_pin, OUTPUT);
}
float read_PH()
{
  float PH = 0;
  for(int i = 0; i < 100; i++){
    PH += (analogRead(PH_pin) * 14.0) / (4095.0);
  }
    return PH / 100;
}
float read_PH_voltage()
{
  return (analogRead(PH_pin) * 3.3) / (4095.0);
}
float read_TDS()
{
  float TDS = 0;
  for(int i = 0; i < 100; i++){
    TDS += analogRead(TDS_pin);
  }
  return TDS / 100;
}

float read_TDS_voltage()
{
  return (read_TDS() * 3.3) / (4095.0);
}
void Update_relay(int relay_pin, bool flag){
  digitalWrite(relay_pin,flag);
}

void send_tank_level(bool state){
  char INSERT_DATA[80];
  
  strcat(INSERT_DATA,"INSERT INTO ");
  strcat(INSERT_DATA, db);
  strcat(INSERT_DATA, ".");
  strcat(INSERT_DATA, "Tank_Water_Level");
  strcat(INSERT_DATA,"(WaterLevel) VALUES (");
  strcat(INSERT_DATA, state ? "1" : "0");
  strcat(INSERT_DATA,");");

  Serial.print("Command: ");
  Serial.println(INSERT_DATA);

  if(conn.connect(server_addr,3306,user,database_pwd)){
    Serial.println();
    delay(1000);
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(INSERT_DATA);
    delete cur_mem;
  }
   else {
    Serial.println("Failed to send data");
   }
  conn.close();
}

void send_data()
{
  char INSERT_DATA[80];
  char buff_PH[20];
  char buff_TDS[20];
  
  strcat(INSERT_DATA,"INSERT INTO ");
  strcat(INSERT_DATA, db);
  strcat(INSERT_DATA, " . ");
  strcat(INSERT_DATA, table_name);
  strcat(INSERT_DATA," (PH, TDS) VALUES ('");
  strcat(INSERT_DATA, gcvt(read_PH(), 4, buff_PH));
  strcat(INSERT_DATA,"','");
  strcat(INSERT_DATA, gcvt(read_TDS(), 4, buff_TDS));
  strcat(INSERT_DATA,"');");

  Serial.print("Command: ");
  Serial.println(INSERT_DATA);
  
  if(conn.connect(server_addr,3306,user,database_pwd)){
    Serial.println();
    delay(1000);
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(INSERT_DATA);
    delete cur_mem;
  }
   else {
    Serial.println("Failed to send data");
   }
  conn.close();
  
}
void Send_level_alert(int level){
  if(!client.connect(host, port)){
    Serial.println("Connection to host failed");
    return;
  }
  
  Serial.println("Connected to server successfully");
  char * msg = "Level ";
  char * buff;
  strcat(msg, itoa(level,buff,10)); // 10 means decimal
  strcat(msg, " ");
  
  client.print(strcat(msg, localIp));

}
void loop()
{
  clock_t start_time = clock();
  
  pump_timer++;
  sensors_timer++;

  if(pump_timer == 19){
    Update_relay(MIXER_RELAY_pin, HIGH);
    Serial.println("mixer ON");
  }
  if(pump_timer >= 24 && pump_timer < 30){
    Update_relay(PUMP_RELAY_pin, HIGH);
    Update_relay(MIXER_RELAY_pin, LOW);
    Serial.println("pump ON");
    Serial.println("mixer OFF");
  }
  if(pump_timer >= 30){
    pump_timer = 0;
    Update_relay(PUMP_RELAY_pin, LOW);
    Serial.println("pump OFF");
  }
  if(sensors_timer >= 3600){
    sensors_timer = 0;
    send_data();   
  }

  if(analogRead(SPRAYER_WATER_LEVEL_pin) > 4000){
    Update_relay(SMALL_PUMP_RELAY_pin, HIGH);
    spray_level_state = true;
  }
  else{
    Update_relay(SMALL_PUMP_RELAY_pin, LOW);
    spray_level_state = false;
  }
  if(analogRead(TANK_WATER_LEVEL_pin) < 4000 && tank_level_state != false){
    send_tank_level(false);
    tank_level_state = false;
  }
  else if(analogRead(TANK_WATER_LEVEL_pin) > 4000 && tank_level_state != true){
    send_tank_level(true);
    tank_level_state = true;
  }

  Serial.println();
  Serial.println();
  Serial.println();
  Serial.println("Live data :");
  Serial.print("TDS : ");
  Serial.println(read_TDS());
  Serial.print("TDS voltage: ");
  Serial.println(read_TDS_voltage());
  Serial.print("PH : ");
  Serial.println(read_PH());
  Serial.print("PH voltage : ");
  Serial.println(read_PH_voltage());
  Serial.print("Tank water level: ");
  Serial.println(tank_level_state ? "High" : "Low");
  Serial.print("Spray water level: ");
  Serial.println(spray_level_state ? "High" : "Low");
  
  clock_t end_time = clock();
  double Elapsed_time = ((double)(end_time - start_time)) / CLOCKS_PER_SEC;
  Serial.print("Elapsed time: ");
  Serial.println(Elapsed_time);
  if(Elapsed_time < 1000){
   delay(1000 - Elapsed_time); // loop every seconds
  }
  else{
    delay(1000);
  }
  
}
