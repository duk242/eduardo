
#include <LiquidCrystal.h>
#include <SPI.h>
#include <Ethernet.h>
#include <EthernetUdp.h>

// Pin Setup
LiquidCrystal lcd(3, 5, 6, 7, 8, 9);
int sensorPin = 0;    // select the input pin for the potentiometer

// Network Setup
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xAA };    // Last Digit changed from examples.
IPAddress ip(192, 168, 0, 113);
EthernetUDP Udp;
IPAddress logServer(192, 168, 0, 104);    // Logging Server, recorder in this case.
unsigned int logServerPort = 13333;
unsigned int arduinoPort = 13334;

int sensorValue = 0;  // variable to store the value coming from the sensor
unsigned long sensorCounter = 0;
int senseOn = 0;
int senseMin = 500; 
float time;
float timeSinceLast = 0;
float current = 0;
float lastTime = 0;
int ethernetEnabled = 1;
int serialEnabled = 0;

void setup()
{
  lcd.begin(20, 4);
  lcd.setCursor(0,3);
  lcd.print("IP: 192.168.0.113");
  if(ethernetEnabled == 1) {
    Ethernet.begin(mac, ip);
    Udp.begin(arduinoPort);
  }
  if(serialEnabled == 1) {
    Serial.begin(115200);
  }
}

void loop() {
  // read the value from the sensor:
  //delay(25);
  sensorValue = analogRead(sensorPin);   
  //Serial.println(sensorValue);
  if(sensorValue >= senseMin) {
    if(senseOn == 0) {
      sensorCounter++;
      timeSinceLast = millis() - lastTime;
      lastTime = millis();        // reset last time counter
      time = timeSinceLast/1000;  // seconds since last tick
      time = 60/time;            // ticks per minute
      time = time*60;            // ticks per hour
      current = time/3600;
      if(serialEnabled == 1) {
        Serial.println(timeSinceLast);
        Serial.println(current);
      }
      
      // Much Effort. Float -> String -> Char Array. Prob a better way to do this.
      char currentChar[20];
      char lastTimeChar[20];
      String blah = String(current);
      blah.toCharArray(currentChar, 20);
      blah = String(lastTime);
      blah.toCharArray(lastTimeChar, 20);
      if(ethernetEnabled == 1) {
        // Send UDP Packet to Logging Server
        Udp.beginPacket(logServer, logServerPort);
        Udp.write("<xml>\n<current>");
        Udp.write(currentChar);
        Udp.write("</current>\n<time>");
        Udp.write(lastTimeChar);
        Udp.write("</time>\n</xml>\n");
        Udp.endPacket();
      }
      senseOn = 1;
    }
  } else {
    senseOn = 0;
  }
  //lcd.clear();  <-- Don't use this, makes it flicker
  
  // Print everything to the LCD
  lcd.setCursor(0,0);
  lcd.print("CC: ");
  lcd.setCursor(4,0);
  lcd.print(sensorCounter);
  lcd.setCursor(0,1);
  lcd.print(current);
  lcd.print(" kWh");
  lcd.setCursor(0,2);
  lcd.print("Sense (");
  lcd.print(senseMin);
  lcd.print("): ");
  lcd.print(sensorValue);
  
  
}
