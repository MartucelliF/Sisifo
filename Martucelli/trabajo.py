import serial
import time

# Conectar al puerto virtual COM4 (el que usaremos para enviar datos a Wokwi)
ser = serial.Serial('COM4', 9600, timeout=1)  # Ajusta el puerto a COM4 o el que hayas creado

# Enviar un mensaje a Wokwi cada 2 segundos
while True:
    mensaje = "Hola desde Python!"  # El mensaje que enviarás
    ser.write(mensaje.encode())  # Enviar el mensaje al puerto serial
    print(f"Datos enviados: {mensaje}")  # Imprimir los datos enviados
    time.sleep(2)  # Espera de 2 segundos antes de enviar nuevamente
