#include <WiFi.h>
#include <ArduinoHttpClient.h>
//#include <SoftwareSerial.h>
#include "arduino_secrets.h"
#include <stdio.h>

// low pressure settings (Better result and more forgiving than high pressure)
int IrrigationTimeOn = 10;
int IrrigationFullCycle = 600;

char* ssid = SECRET_SSID;
char* password = SECRET_PWD;
char* serverAdress = "";
int port = 8080;

WiFiClient wifi;
WebSocketClient client = WebSocketClient(wifi, serverAdress, port);
int status = WL_IDLE_STATUS;
int count = 0;

const int Sprinkler_Pump_Pin = 32;
bool Sprinkler_Pump_Status = false;

const int Drip_Pump_Pin = 33;
bool Drip_Pump_Status = false;


const int Drain_Tank_Water_Level_Sensor_Pin = 36;
bool Drain_Tank_Water_Level = LOW;

const int Drain_Pump_Pin = 25;
bool Drain_Pump_Status = false;

const int Tank1_Mixer_Relay_Pin = 26;
const int Tank1_PH_Sensor_Pin = 39;
const int Tank1_PPM_Sensor_Pin = 34;
const int Tank1_Water_Level_Sensor_Pin_RX = 5;
const int Tank1_Water_Level_Sensor_Pin_TX = 8;

bool Tank1_Mixer_Status = false;
float Tank1_Water_Level = 0;
float Tank1_PH = 0;
float Tank1_PPM = 0;

const int Tank2_Mixer_Relay_Pin = 27;
const int Tank2_PH_Sensor_Pin = 14;
const int Tank2_PPM_Sensor_Pin = 12;
const int Tank2_Water_Level_Sensor_Pin_RX = 10;
const int Tank2_Water_Level_Sensor_Pin_TX = 11;

bool Tank2_Mixer_Status = false;
float Tank2_Water_Level = 0;
float Tank2_PH = 0;
float Tank2_PPM = 0;

int IrrigationTime = 0;
int DatabaseTime = 0; // we want to send data every hour

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  delay(10);

  Connect_To_Hotspot();

  // Set Output Pins
  pinMode(Sprinkler_Pump_Pin, OUTPUT);
  pinMode(Drip_Pump_Pin, OUTPUT);
  pinMode(Drain_Pump_Pin, OUTPUT);
  pinMode(Tank1_Mixer_Relay_Pin, OUTPUT);
  pinMode(Tank2_Mixer_Relay_Pin, OUTPUT);

  // Set Input Pins
  pinMode(Drain_Tank_Water_Level_Sensor_Pin, INPUT);
  
  pinMode(Tank1_PH_Sensor_Pin, INPUT);
  pinMode(Tank1_PPM_Sensor_Pin, INPUT);
  pinMode(Tank1_Water_Level_Sensor_Pin_RX, INPUT);
  pinMode(Tank1_Water_Level_Sensor_Pin_TX, INPUT);
  
  pinMode(Tank2_PH_Sensor_Pin, INPUT);
  pinMode(Tank2_PPM_Sensor_Pin, INPUT);
  pinMode(Tank2_Water_Level_Sensor_Pin_RX, INPUT);
  pinMode(Tank2_Water_Level_Sensor_Pin_TX, INPUT);
}
/*--------------------------------------------------------------------------------------
 * 
 *  Sensor data reading
 * 
 */
float Read_Sensor_Data(int Pin){
  return digitalRead(Pin);
}

float Read_Water_Level(int RX_Pin, int TX_Pin){
  // https://dronebotworkshop.com/waterproof-ultrasonic/
}
/*-------------------------------------------------------------------------------
 * 
 *  Switches pumps, or motors ON/OFF
 * 
 */
void Change_Motor_Status(int Pin, bool flag){
  digitalWrite(Pin, !flag);
}
/*-------------------------------------------------------------------------------
 * 
 * Server Things
 *
 */
void Connect_To_Hotspot(){
  
  // Connect to Wi-Fi
  Serial.println("Connecting to WiFi...");
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP()); // Print the IP address assigned to the Arduino
  
}

void SendDataToDatabase(){
  float Tank1_PH = Read_Sensor_Data(Tank1_PH_Sensor_Pin);
  float Tankk1_PPM = Read_Sensor_Data(Tank1_PPM_Sensor_Pin);
}

void TreatCommand(String Command){
  /*
   * Possible commands: 
   *  1 - "Reset Irrigation settings 'IrrigationTimeOn' 'IrrigationFullCycle'"
   *  2 - "Get data 'name of variable'"
   * 
   * 
   */
}
/*-------------------------------------------------------------------------
 * 
 *  Loop 
 * 
 */
 
void loop() {
  client.begin();

  while(client.connected()){


    // check if a messagge is available to be received
    int messageSize = client.parseMessage();

    if (messageSize > 0) {
      Serial.println("Received a message:");
      String message = client.readString();
      Serial.println(message);
      TreatCommand(message);
    }


      if(IrrigationTime == IrrigationFullCycle){
        IrrigationTime = 0;
      }
      else{
        IrrigationTime++;
      }
      if(DatabaseTime == 3600){
        DatabaseTime = 0;
        SendDataToDatabase();
      }
      
      delay(1000);
  }
}
