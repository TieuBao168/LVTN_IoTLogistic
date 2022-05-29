#include <Arduino.h>
#include <painlessMesh.h>
#include <WiFiManager.h>
#include <DHTesp.h>
#include <math.h>

#define MESH_PREFIX     "IoTLogistic"
#define MESH_PASSWORD   "IoTLogistic2022"
#define MESH_PORT   5556

DHTesp dht;

unsigned long previousMillis = 0;  
const long interval = 1000; // giá trị delay (milliseconds)
int consider = 0;

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
  // Serial.println("Start Sending....");

  // delay(dht.getMinimumSamplingPeriod());
  // recent.humidity = round(dht.getHumidity()*100)/100;
  // recent.temperature = roundf(dht.getTemperature()*100)/100;

    // Serializing in JSON Format
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
  
  Serial.println("Message from Node Leaf: ");
  Serial.println(msg);

  //taskSendMessage.setInterval(TASK_SECOND * 5);
}

// void sendMessageWarn(){
//   Serial.println();
//   DynamicJsonDocument doc(1024);

//   doc["Node"] = 4;
//   doc["Temperature"] = recent.temperature;
//   doc["Humidity"] = recent.humidity;

//   String msg ;
//   serializeJson(doc, msg); 
//   // uint32_t id = 3323046497; // ID of RootNode
//   mesh.sendBroadcast(msg);
// }

// Needed for painless library
void receivedCallback(uint32_t from, String &msg ) {
  Serial.println();
  Serial.print("Message = ");
  Serial.print(msg);

  if((msg == "Begin")||(msg == "Error")){
    sendMessage(from, "Single");
  } else if(msg == "Warn"){
    sendMessage(0, "Broadcast");
  } else if(msg == "Again"){
      mesh.sendSingle(from, "Warn");
  } else {
    String json = msg.c_str();
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, json);
    if (error)
    {
      mesh.sendSingle(from, "Again");
    }
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
  previous.temperature = recent.temperature;
  previous.humidity = recent.humidity;

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
  if (currentMillis - previousMillis >= 5*interval) {
    previousMillis = currentMillis;
    compareData();
  }
}

void compareData(){
  // static int skip = 0;
  recent.humidity = roundf(dht.getHumidity()*100)/100;
  recent.temperature = roundf(dht.getTemperature()*100)/100;
  if((abs(recent.temperature - previous.temperature) > 2.0)||
  (abs(recent.humidity - previous.humidity) > 5.0)){
    mesh.sendBroadcast("Warn");
    sendMessage(0, "Broadcast");
    previous.temperature = recent.temperature;
    previous.humidity = recent.humidity;
  }
}