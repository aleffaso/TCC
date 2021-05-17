#include <ESP8266WiFi.h>
#include <Stepper.h> 

const char* ssid     = "VIVO-6658";
const char* password = "33D7296658";

const char* host = "192.168.15.3";

double celulaOxigenio, sensor;
String ID;

const int stepsPerRevolution = 500; 
int n = 5;

Stepper myStepper(stepsPerRevolution, 16,4,5,0); 

void setup() {
  Serial.begin(9600);
  Serial.println(WiFi.macAddress());
  myStepper.setSpeed(60);
  delay(10);
  
  sensor=0;

  // We start by connecting to a WiFi network

  Serial.println();
  Serial.println();
  Serial.print("Conectando com ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi conectado");  
  Serial.println("Endereco IP: ");
  Serial.println(WiFi.localIP());
}

void loop() {

  //---------------------------------------------//
  // ESPAÇO RESERVADO PARA LEITURA DO SENSOR  //


  Serial.flush();
  //Serial.println(sin(sensor1));
  celulaOxigenio = cos(sensor); 
  sensor += .05;
  
 
  if(celulaOxigenio >= 2*3.14)
    celulaOxigenio = 0;

    ID = WiFi.macAddress();

  //--------------------------------------------//
  
  Serial.print("connectando com ");
  Serial.println(host);
  
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 8888;
  if (!client.connect(host, httpPort)) {
 //   Serial.println("Falha na conexao");
    return;
  }
  
  // CRIANDO A  REQUISIÇÃO COM O CLIENTE
  String  url = "/nodemcu/salvar.php?";
          url += "sensor1=";
          url += celulaOxigenio;
          url += "&idDispositivo=";
          url += ID;
  
  Serial.print("Requisitando URL: ");
  Serial.println(url);
  
  // CRIANDO REQUISIÇÃO COM O SERVIDOR
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: close\r\n\r\n");
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
    }
  }
  // LER TODAS AS INFORMAÇÕES E AS APRESENTA NO MONITOR SERIAL
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.println(line);
    Serial.println(WiFi.macAddress());
    Serial.println();
    Serial.println();
    Serial.println();

    // RECEBE A REQUISIÇÃO PARA GIRAR O MOTOR NO SENTIDO HORÁRIO OU ANTI-HORÁRIO

    if (line.indexOf("/MOTOR=1") != -1)  {
      myStepper.step(1024);
    }
 
    if (line.indexOf("/MOTOR=-1") != -1)  {
     myStepper.step(1024);
    }

  // APRESNETA A RESPOSTA DO SERVIDOR

    if(line.indexOf("salvo_com_sucesso") != -1){
      Serial.println();
      Serial.println("Salvo com sucesso");  
    }else if(line.indexOf("erro_ao_salvar") != -1){
      Serial.println("Ocorreu um erro");  
    }

  }
  
  Serial.println();
  Serial.println("Fechando conexao");

  delay(1000);

}

