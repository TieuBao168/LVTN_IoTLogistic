#include <Arduino.h>
#include <painlessMesh.h>
#include <WiFiManager.h>
#include <DHTesp.h>
#include <math.h>

#define MESH_PREFIX     "IoTLogistic"
#define MESH_PASSWORD   "IoTLogistic2022"
#define MESH_PORT   5555

DHTesp dht;

unsigned long previousMillis, pre, precious = 0;  
const long interval = 1000; // giá trị delay (milliseconds)
int consider[2] = {1,1};
int skip = 0;
uint32_t idRootnode;

typedef struct{
  float humidity, temperature = 0;
} Data;
Data previous, recent;

Scheduler userScheduler; 
painlessMesh  mesh;
void sendMessage(uint32_t id, String Destination); // Prototype so PlatformIO doesn't complain
//Task taskSendMessage( TASK_SECOND * 1 , TASK_FOREVER, &sendMessage );
void compareData();

void sendMessage(uint32_t id, String Destination){
  Serial.println();
  DynamicJsonDocument doc(1024);
  doc["Node"] = 2;
  doc["Temperature"] = recent.temperature;
  doc["Humidity"] = recent.humidity;

  String msg ;
  serializeJson(doc, msg); 
  // uint32_t id = 3323046497; // ID of RootNode
  if(Destination == "Single"){
    mesh.sendSingle(id, msg);
  } else if(Destination == "Broadcast"){
    mesh.sendBroadcast(msg);
  }
  
  pre = millis();
  consider[0] = 0;

  Serial.println("Message from Node Leaf: ");
  Serial.println(msg);

  //taskSendMessage.setInterval(TASK_SECOND * 5);
}

// Needed for painless library
void receivedCallback(uint32_t from, String &msg ) {
  Serial.println();
  Serial.print("Message = ");
  Serial.print(msg);

  if(msg == "Begin"){
    sendMessage(from, "Single");
    idRootnode = from;
    consider[1] = 1;
  } else if(msg == "Warn"){
    
  } else if(msg == "Ok"){
    consider[0] = 1;
  } else if(msg == "Again"){
    mesh.sendSingle(from, "Warn");
  } else {
    mesh.sendSingle(from, "Again");
  }
}

void newConnectionCallback(uint32_t nodeId) {
  // Serial.printf("--> startHere: New Connection, nodeId = %u\n", nodeId);
}
void changedConnectionCallback() {
  // Serial.printf("Changed connections\n");
}
void nodeTimeAdjustedCallback(int32_t offset) {
  // Serial.printf("Adjusted time %u. Offset = %d\n", mesh.getNodeTime(), offset);
}

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);

  dht.setup(D3, DHTesp::DHT11);
  recent.temperature = roundf(dht.getTemperature()*100)/100;
  recent.humidity = roundf(dht.getHumidity()*100)/100;
  // previous.temperature = recent.temperature;
  // previous.humidity = recent.humidity;

  mesh.setDebugMsgTypes( ERROR | STARTUP );  
  mesh.init( MESH_PREFIX, MESH_PASSWORD, &userScheduler, MESH_PORT );
  mesh.onReceive(&receivedCallback);
  mesh.onNewConnection(&newConnectionCallback);
  mesh.onChangedConnections(&changedConnectionCallback);
  mesh.onNodeTimeAdjusted(&nodeTimeAdjustedCallback);

}

void loop() {
  // put your main code here, to run repeatedly:
  // it will run the user scheduler as well
  mesh.update();
  
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= 10*interval) {
    previousMillis = currentMillis;
    compareData();
  }
  // if (currentMillis - pre >= 5*interval) {
  //   if(consider[0] == 0){
  //     sendMessage(idRootnode, "Single");
  //   }
  // }
  // if (currentMillis - precious >= 5*interval) {
  //   if(consider[1] == 0){
  //     mesh.sendBroadcast("Warn");
  //     consider[1] = 0; precious = currentMillis;
  //   }
  // }
}

void compareData(){
  recent.humidity = roundf(dht.getHumidity()*100)/100;
  recent.temperature = roundf(dht.getTemperature()*100)/100;
  if(skip==0){
    previous.temperature = recent.temperature;
    previous.humidity = recent.humidity;
    skip=1;
  }
  if((abs(recent.temperature - previous.temperature) > 2.0)||
  (abs(recent.humidity - previous.humidity) > 5.0)){
    mesh.sendBroadcast("Warn");
    consider[1] = 0; precious = millis();
    previous.temperature = recent.temperature;
    previous.humidity = recent.humidity;
  }
}