// ESP8266 WiFi Connection
#include "ESP8266WiFi.h"

// WiFi SSID and Password (wifiConfig.h stores my wifi SSID and Password)
#include "wifiConfig.h";
const char* ssid = MY_SSID; // WiFi Name
const char* password = MY_PASSWORD; // WiFi Password
const char* server = MY_LOCAL_SERVER; // URL for the API to POST data

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

// variables for testing: randomly generated variables by time of day.
int dailyHigh = 96;
int dailyHumidity = 30;
int randomStorm = 0;
int hourOfDay = 0;
int randomWeatherData[3] = {68,88,30};

void loop() {
  if(WiFi.status() == WL_CONNECTED){ // Make sure we are connected to WiFi
    HTTPClient http; // Declare HTTPClient Object

    http.begin(server);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // weather variables
    byte waterTemp, airTemp, humidity;

    // create random weather data for testing
    randomWeather();
    waterTemp = randomWeatherData[0];
    airTemp = randomWeatherData[1];
    humidity = randomWeatherData[2];


    int httpCode = 0;
    while(httpCode != 200){
      // keep sending POST data every five seconds until we get a response from the server
      httpCode = http.POST( // POST Request
          "waterTemp=" + String(waterTemp)
          + "&airTemp=" + String(airTemp)
          + "&humidity=" + String(humidity)
          + "&address=" + String(WiFi.macAddress()));
      delay(5000);
    }
    String response = http.getString();

    Serial.println(response);

    http.end();
  } else {
    Serial.println("WiFi Connection Error");
  }

//  delay(100);

}

void randomWeather(){
  // Generates random weather data to simulate the Month of July in the Salt Lake Region for web portal testing
  
  // Change Daily High
  if(hourOfDay == 23){
    hourOfDay = 0;
    
    // reset after storm
    if(dailyHigh < 85){
      dailyHigh = 96;
      dailyHumidity = 30;
    }
  
    // calculate the random chance of having a storm which reduces air temp and humidity
    int randomStormCalculation = randomStorm * random(0,2);
    if(randomStormCalculation > 15){
      // if 'thunder storm' happens, lower the temperature accordingly
      dailyHigh -= 20;
      dailyHumidity += 40;
    } else {
      // if 'thunder storm' does not happen, randomly choose a temperature close to previous day temp
      if(dailyHigh >= 100){
        // scew temperature towards cooler temps if previous day is abnormally hot
        dailyHigh += random(-3,1);
      } else if(dailyHigh <= 90){
        // scew temperature towards cooler temps if previous day is abnormally cool
        dailyHigh += random(-1,6);
      } else{
        // randomly raise or lower temperature within a probable range
        dailyHigh += random(-3,3);
      }
    }
  }

  // Assume user has addressed the water heat issue and reset the temperature
  if(randomWeatherData[0] > 85){
    randomWeatherData[0] -= 15;
  }

  // hourly scew changes the simulated hourly temperature compared to the daily high
  double hourlyTempScew[24] = {0.82,0.78,0.74,0.69,0.68,0.68,0.68,0.69,0.71,0.74,0.78,0.82,0.88,0.94,0.96,0.97,0.98,0.99,1,0.99,0.97,0.95,0.92,0.88};

  randomWeatherData[1] = dailyHigh * hourlyTempScew[hourOfDay] + random(-1,1);
  if(randomWeatherData[1] > 90){
    randomWeatherData[0] += random(0,3);
  } else if(randomWeatherData[0] < 64){
    randomWeatherData[0] += random(-3,0);
  }
  randomWeatherData[2] = dailyHumidity + random(-1,1);

  hourOfDay++;
}

