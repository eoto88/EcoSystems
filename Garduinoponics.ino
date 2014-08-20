/*
 
 Created 5 July 2014
 by Eric Turcotte
 
 */

#include <Ethernet.h>
#include <SPI.h>
#include <Wire.h>
#include <Time.h>
#include <DS1307RTC.h>
#include <OneWire.h>
#include <DallasTemperature.h>

tmElements_t tm;

boolean DEBUG = true;

/************ ETHERNET ************/
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192, 168, 2, 20);
String pass = "a2cb2ef5-8f5c-40e8-8526-8582eb4b844f";
String data;
EthernetClient client;
String host = "yourhost.com";
// Initialize the Ethernet server library with the IP address and port you want to use (port 80 is default for HTTP):
EthernetServer server(80);

/************ SENSORS ************/
const int lightSensor = A0;
const int ONE_WIRE_BUS_PIN = 7;
OneWire oneWire(ONE_WIRE_BUS_PIN);
DallasTemperature sensors(&oneWire);
DeviceAddress tankTemperatureSensor = { 0x28, 0x60, 0xE5, 0x09, 0x06, 0x00, 0x00, 0x97 }; 
DeviceAddress roomTemperatureSensor = { 0x28, 0x5B, 0x56, 0x0A, 0x06, 0x00, 0x00, 0x0B };

/************ RELAYS ************/
const int lightRelay = 2;
const int pumpRelay = 3;
const int fanRelay = 4;
const int heaterRelay = 5;
const int RELAY_ON = LOW;
const int RELAY_OFF = HIGH;

/************ VARIABLES ************/
unsigned long lastPostHourMillis = 0;
unsigned long lastPostQuarterHourMillis = 0;
unsigned long lastPostStillAliveMillis = 0;
unsigned long lastPostTwoMinutesMillis = 0;
boolean sunriseSent = false;
boolean sunsetSent = false;
const long hourMillis = 3600000; /* 3600000 / 60000 (For testing) */
const long quarterHourMillis = 900000; /* 900000 / 15000 (For testing) */
const long twoMinutesMillis = 120000; /* 120000 / 60000 (For testing) */

void setup() {
  // Open serial communications and wait for port to open:
  Serial.begin(9600);
  
  // start the Ethernet connection and the server:
  Ethernet.begin(mac, ip);
  server.begin();
  Serial.print(F("server is at "));
  Serial.println(Ethernet.localIP());
  
  sensors.begin();
  sensors.setResolution(tankTemperatureSensor, 10);
  sensors.setResolution(roomTemperatureSensor, 10);
  
  pinMode(lightRelay, OUTPUT);
  pinMode(pumpRelay, OUTPUT);
}

void loop() {
  unsigned long currentMillis = millis();
  int lightSensorValue = analogRead(lightSensor);
    
  if( (currentMillis - lastPostHourMillis) >= hourMillis ) {
    postData("roomTemperature=" + strTemperature(roomTemperatureSensor) + "&tankTemperature=" + strTemperature(tankTemperatureSensor), "temperature");
    
    lastPostHourMillis = currentMillis;
    lastPostStillAliveMillis = currentMillis;
  }
  
  if ( (currentMillis - lastPostTwoMinutesMillis) >= twoMinutesMillis ) {
    float roomTemp = getTemperature(roomTemperatureSensor);
    boolean fanValue = ( roomTemp > 22.0 );
    digitalWrite(fanRelay, fanValue);
    String fanStatus = fanValue ? "0" : "1";
    
    float tankTemp = getTemperature(tankTemperatureSensor);
    boolean heaterValue = ( tankTemp < 20.0 );
    digitalWrite(heaterRelay, heaterValue);
    String heaterStatus = heaterValue ? "0" : "1";
    
    postData("fanStatus=" + fanStatus + "&heaterStatus=" + heaterStatus, "heaterAndFanStatus");
    
    lastPostTwoMinutesMillis = currentMillis;
    lastPostStillAliveMillis = currentMillis;
  }
  
  if( ! sunriseSent && lightSensorValue > 400 && getHour() < 12) { // Day
    postData("", "sunrise");
    sunriseSent = true;
    sunsetSent = false;
    digitalWrite(lightRelay, RELAY_ON);
    digitalWrite(pumpRelay, RELAY_ON);
  } else if(!sunsetSent && lightSensorValue < 400 && getHour() > 12) { // Night
    postData("", "sunset");
    sunsetSent = true;
    sunriseSent = false;
    digitalWrite(lightRelay, RELAY_OFF);
    digitalWrite(pumpRelay, RELAY_OFF);
  }
  if( (currentMillis - lastPostQuarterHourMillis) >= quarterHourMillis ) {
    postData("sunlight=" + (String)lightSensorValue, "sunlight");
    
    if( ! sunsetSent) {
      boolean pumpValue = !digitalRead(pumpRelay);
      digitalWrite(pumpRelay, pumpValue);
      String pumpStatus = pumpValue ? "0" : "1";
      postData("pumpStatus=" + pumpStatus, "pumpStatus");
    }
    
    lastPostQuarterHourMillis = currentMillis;
    lastPostStillAliveMillis = currentMillis;
  }
  
  if( (currentMillis - lastPostStillAliveMillis) >= 30000 ) {
    postData("", "still-alive");
    lastPostStillAliveMillis  = currentMillis;
  }

}

float getTemperature(DeviceAddress deviceAddress) {
    sensors.requestTemperatures();
    return sensors.getTempC(deviceAddress);
}

String strTemperature(DeviceAddress deviceAddress) {
    float currentTemp = getTemperature(deviceAddress);
    char buffer[10];
    return dtostrf(currentTemp, 4, 1, buffer);
}

time_t getDatetime() {
  if (RTC.read(tm)) {
    return makeTime(tm);
  } else {
    if (RTC.chipPresent()) {
      Serial.println("The DS1307 is stopped.  Please run the SetTime");
      Serial.println("example to initialize the time and begin running.");
      Serial.println();
    } else {
      Serial.println("DS1307 read error!  Please check the circuitry.");
      Serial.println();
    }
    delay(9000);
  }
}

int getHour() {
  if (RTC.read(tm)) {
    return tm.Hour;
  }
  return 0;
}

boolean postData(String data, String action) {
  if(action.length() > 0) {
    if(data.length() > 0) {
      data += "&";
    }
    
    data += "action=" + (action);
    data += "&datetime=" + (String)getDatetime();
    data += "&pass=" + (pass);
    if(DEBUG) {
      data += "&debug=true";
    }
  }
  
  if (client.connect("yourhost.com", 80)) {
    if(DEBUG) {
      Serial.println(F("Connected to yourhost.com..."));
      Serial.println();
    }

    client.print("POST /garduinoponics/ajax/postData HTTP/1.1\n");
        
    client.print("Host: "+ host +"\n");                          
    client.print("Connection: close\n");
     
    client.print("Content-Type: application/x-www-form-urlencoded\n");
    client.print("Content-Length: ");
    
    client.print(data.length());                                            
    client.print("\n\n");
    client.print(data);
    
    if(DEBUG) {
      Serial.println(data);
      Serial.println(F("disconnected"));
    }
    client.stop();
  } else {
    Serial.println(F("Connection Failed."));
    Serial.println();
  }
}
