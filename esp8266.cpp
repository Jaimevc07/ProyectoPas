#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>

/*
In the ESP8266, D3 pin is RST_PIN and
D4 pin is SS_PIN
*/

#define RST_PIN D3
#define SS_PIN D4

MFRC522 reader(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key key;

LiquidCrystal_I2C lcd(0x27, 16, 2);

// Credentials to connect to the wifi network
const char *ssid = "TALLER";
const char *password = "Futuro732**innovar";
/*
The ip or server address. If you are on localhost, put your computer's IP (for example http://192.168.1.65)
If the server is online, put the server's domain.
*/
const String SERVER_ADDRESS = "http://asistenciainnovar.online";
void setup() {

  lcd.init();
  lcd.clear();
  lcd.backlight();

  Serial.begin(9600);
  Serial.print("init");
  // Connect to wifi
  WiFi.begin(ssid, password);

  lcd.setCursor(2, 0);  //Set cursor to character 2 on line 0
  lcd.print("Conectando...");

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
  }

  SPI.begin();

  reader.PCD_Init();
  // Just wait some seconds...
  delay(4);
  // Prepare the security key for the read and write functions.
  // Normally it is 0xFFFFFFFFFFFF
  // Note: 6 comes from MF_KEY_SIZE in MFRC522.h
  for (byte i = 0; i < 6; i++) {
    key.keyByte[i] = 0xFF;  //keyByte is defined in the "MIFARE_Key" 'struct' definition in the .h file of the library
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Wifí Conectado!");
  delay(1500);
  lcd.clear();
}

void loop() {
  // If not connected, we don't need to read anything, that would be unnecessary
  if (WiFi.status() != WL_CONNECTED) {
    return;
  }

  lcd.noBacklight();

  // But, if there is a connection we check if there's a new card to read

  // Reset the loop if no new card present on the sensor/reader. This saves the entire process when idle.
  if (!reader.PICC_IsNewCardPresent()) {
    return;
  }

  // Select one of the cards. This returns false if read is not successful; and if that happens, we stop the code
  if (!reader.PICC_ReadCardSerial()) {
    return;
  }

  /*
    At this point we are sure that: there is a card that can be read, and there's a
    stable connection. So we read the id and send it to the server
  */

  String serial = "";
  for (int x = 0; x < reader.uid.size; x++) {
    // If it is less than 10, we add zero
    if (reader.uid.uidByte[x] < 0x10) {
      serial += "0";
    }
    // Transform the byte to hex
    serial += String(reader.uid.uidByte[x], HEX);
    // Add a hypen
    if (x + 1 != reader.uid.size) {
      serial += "-";
    }
  }
  // Transform to uppercase
  serial.toUpperCase();

  // Halt PICC
  reader.PICC_HaltA();
  // Stop encryption on PCD
  reader.PCD_StopCrypto1();

  WiFiClient client;
  HTTPClient http;

  // Send the tag id in a GET param
  const String full_url = SERVER_ADDRESS + "/rfid_register.php?serial=" + serial;
  http.begin(client, full_url);
  // Make request
  int httpCode = http.GET();
  if (httpCode > 0) {
    if (httpCode == HTTP_CODE_OK) {
      Serial.printf("HTTP OK ");
      const String &payload = http.getString().c_str();  //Get the request response payload
      Serial.println("Request is OK! The server says: ");
      Serial.println(payload);

      lcd.clear();
      lcd.backlight();
      lcd.setCursor(4, 0);
      lcd.print("Registrando...");
      lcd.setCursor(4, 1);
      lcd.print("Espere un momento");
      for (int i = 0; i < 9; i++) {
        lcd.scrollDisplayLeft();
        delay(200);
      }

      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Asistencia");
      lcd.setCursor(0, 1);
      lcd.print("Registrada!");
      delay(1000);

      lcd.clear();
      lcd.setCursor(2, 0);
      lcd.print("Buen dia!");
      lcd.setCursor(2, 1);
      lcd.print(serial);
      delay(1200);
      lcd.clear();

    } else {
      const String &payload = http.getString().c_str();  //Get the request response payload
      Serial.println("Request is not OK! The server says: ");
      Serial.println(payload);

      lcd.clear();
      lcd.backlight();
      lcd.setCursor(0, 0);
      lcd.print("Error Server");
      lcd.setCursor(0, 1);
      lcd.print("Reconectando :c");
      delay(1000);
      lcd.clear();
    }
  } else {
    Serial.printf("HTTP NOTHING ");

    lcd.clear();
    lcd.backlight();
    lcd.setCursor(0, 0);
    lcd.print("Error 'HTTP NOTHING' ");
    lcd.setCursor(0, 1);
    lcd.print("Reconectando...");
    delay(1000);
    lcd.clear();
  }
  http.end();  //Close connection
}