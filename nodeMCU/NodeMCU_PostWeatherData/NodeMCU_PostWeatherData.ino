// ESP8266 WiFi Connection
#include "ESP8266WiFi.h"

// WiFi SSID and Password (wifiConfig.h stores my wifi SSID and Password)
#include "wifiConfig.h";
const char* ssid = MY_SSID;
const char* password = MY_PASSWORD;
const char* server = MY_LOCAL_SERVER;

// ESP8266 HTTP Client (for Post and Get Requests)
#include <ESP8266HTTPClient.h>

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  // Connect to WiFi
  Serial.print("Connecting to ");
  Serial.print(ssid); Serial.println(" ...");
  int i = 0;
  while (WiFi.status() != WL_CONNECTED) { // Wait for the Wi-Fi to connect
    delay(1000);
    Serial.print(++i); Serial.print(' ');
  }
  Serial.println('\n'); Serial.println("Connection established!");  Serial.print("IP address:\t");
  Serial.println(WiFi.localIP()); // Send the IP address of the ESP8266 to the computer
  
}

void loop() {
  if(WiFi.status() == WL_CONNECTED){ // Make sure we are connected to WiFi
    HTTPClient http; // Declare HTTPClient Object

    http.begin(server);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST("name=Brandon Walker&quantity=10"); // POST Request
    String response = http.getString();

    Serial.println(httpCode);
    Serial.println(response);

    http.end();
  } else {
    Serial.println("WiFi Connection Error");
  }

  delay(3000);

}
